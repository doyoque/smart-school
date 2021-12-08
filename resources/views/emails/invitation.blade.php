<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart School Invitation</title>
</head>
<body>
    <p>Hello {{$data['name']}}</p>
    <p>Here's the account and link for login school system</p>
    <br>
    <p>school id: {{$data['school_id']}}</p>
    <p>username: {{$data['username']}}</p>
    <p>password: {{$data['password']}}</p>
    <p><a href="{{$data['url']}}">Login</a></p>
</body>
</html>
