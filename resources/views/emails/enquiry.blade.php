<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiry Confirmation</title>
</head>
<body>
    <h1>Thank you for your enquiry, {{ $enquiry->name }}</h1>
    <p>We have received your enquiry for the following product:</p>
    <ul>
        <li><strong>Product:</strong> {{ $enquiry->product->name }}</li>
        <li><strong>Details:</strong> {{ $enquiry->detail }}</li>
    </ul>
    <p>We will get back to you shortly.</p>

    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>
