<?php

namespace App\Exports;

use Carbon\Carbon;

use App\Models\Event;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Contracts\Queue\ShouldQueue;

use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class EventsExport extends DefaultValueBinder implements FromQuery,
    ShouldQueue, WithColumnFormatting, WithMapping,
    WithHeadings, ShouldAutoSize, WithCustomValueBinder,
    WithStyles, WithDrawings
{
    use Exportable;

    public function forYear(int $year)
    {
        $this->year = $year;

        return $this;
    }

    // customize
    public function map($events): array
    {
        return [
                $events->eventName,
                $events->location,
                Date::dateTimeToExcel(Carbon::parse($events->date))
        ];
    }

    public function headings(): array
    {
        return [
            'eventName',
            'location',
            'date',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            // 'D' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 11]],

            // Styling a specific cell by coordinate.
            'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/img/magmatrailrun2018.jpg'));
        $drawing->setHeight(120);
        $drawing->setCoordinates('F1');

        return $drawing;
    }

    public function query()
    {
        return Event::query()->whereYear('date', $this->year);
    }

    // public function view(): View
    // {
    //     return view('exports.events', [
    //         'events' => Event::whereYear('date', $this->year)->get()
    //     ]);
    // }
}