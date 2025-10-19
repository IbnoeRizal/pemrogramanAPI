<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password WiFi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="text-center">
        @if (request('password'))
            <div class="alert alert-success shadow-sm">
                Password WiFi kamu: <strong>{{ request('password') }}</strong>
            </div>
        @endif

        @if (request('error'))
            <div class="alert alert-danger shadow-sm">
                {{ request('error') }}
            </div>
        @endif
    </div>

</body>
</html>
