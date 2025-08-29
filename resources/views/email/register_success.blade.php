<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['title'] }}</title>
</head>
<body>
    <p>Hi {{ $details['receiver_name'] }},</p>
    <p>{{ $details['body'] }}</p>
    <p>Best Regards,</p>
    <p>{{ $details['from_name'] }}</p>
</body>
</html>