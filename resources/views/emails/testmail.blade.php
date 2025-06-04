<!DOCTYPE html>
<html>
<head>
    <title>Account Created</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Your account has been successfully created in our system.</p>
    <p>You can now log in using your user: {{ $user->user}}</p>
    <p>Thank you for joining us!</p>
</body>
</html>
