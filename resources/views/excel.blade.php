<table>
    <thead>
    <tr>
        <th>Number</th>
        <th>Time</th>
        <th>Schedule</th>
        <th>Execution</th>
        <th>Aborted</th>
    </tr>
    </thead>
    <tbody>
    @foreach($schedules as $key => $schedule)
        <tr>
            <td>{{$key}}</td>
            <td>{{$times[$key]}}</td>
            <td>{{$schedule}}</td>
            <td>{{$executions[$key]}}</td>
            <td>{{key_exists($key,$aborts)? implode(',',$aborts[$key]) : "-"}}</td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td>{{$totalTime}}</td>
        <td>{{$algorithm}}</td>
    </tr>
    </tbody>
</table>
