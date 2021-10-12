<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <table>
    <thead>
    <tr>
        <th>eventName</th>
        <th>location</th>
        <th>date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($events as $event)
        <tr>
            <td>{{ $event->eventName }}</td>
            <td>{{ $event->location }}</td>
            <td>{{ $event->date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>