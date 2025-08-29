<!DOCTYPE html>
<html>
<head>
</head>
<body>
    @isset($mail['salutation'])
        <p>{{ $mail['salutation']}} {{ $mail['to_name'] }},</p>
    @endisset

    @isset($mail['title'])
        <h4>{{ $mail['title'] }}</h4>
    @endisset

    @isset ($mail['body'])
        <p>{{ $mail['body'] }}</p>
    @endisset

    @isset ($mail['subbody'])
        <p>{{ $mail['subbody'] }}</p>
    @endisset

    @isset ($mail['closing'])
        <p>{{ $mail['closing'] }}
        @isset ($mail['from_name'])
            <span>,</span>
        @else
            <span>.</span>
        @endisset
        </p>
        <p>{{ $mail['from_name'] }}</p>
    @endisset

</body>
</html>
