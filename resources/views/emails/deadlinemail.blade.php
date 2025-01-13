<!DOCTYPE html>
<html>

<head>
    <title>Upcoming Deadlines</title>
</head>

<body>
    <h1>Hello, {{ $user->name }}!</h1>
    <p>This is a reminder of your upcoming report deadlines:</p>
    @foreach ($research as $research)
        <h2>research: {{ $research->title }}</h2>
        <ul>
            @if ($research->progress_report_deadline && \Carbon\Carbon::parse($research->progress_report_deadline)->isFuture())
                <li>
                    <strong>Progress Report Deadline:</strong>
                    {{ \Carbon\Carbon::parse($research->progress_report_deadline)->format('F j, Y g:i A') }}
                </li>
            @endif
            @if ($research->final_report_deadline && \Carbon\Carbon::parse($research->final_report_deadline)->isFuture())
                <li>
                    <strong>Final Report Deadline:</strong>
                    {{ \Carbon\Carbon::parse($research->final_report_deadline)->format('F j, Y g:i A') }}
                </li>
            @endif
        </ul>
    @endforeach
    <p>Please ensure all tasks are completed on time.</p>
    <p>Thank you!</p>
</body>

</html>
