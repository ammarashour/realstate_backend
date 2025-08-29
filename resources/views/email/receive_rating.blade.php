<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['title'] }}</title>
</head>
<body>
    <p>Hi {{ $details['receiver_name'] }},</p>
    <p>Received rating from {{ $details['review_user_name'] }}</p>
    <p>Best Regards,</p>
    <p>{{ env('MAIL_FROM_NAME') }}</p>
</body>
</html>