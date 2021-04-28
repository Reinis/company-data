<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search</title>
</head>
<body>
<div>
    <form action="/search" method="post">
        @csrf
        <label for="regcode"></label>
        <input type="text" name="regcode" id="regcode">
        <input type="submit" name="submit" value="Search">
    </form>
    @if($message = Session::get('error'))
        <div>{{ $message }}</div>
    @endif
</div>
<div>
    @if ($company = Session::pull('company'))
        {{ $company }}
    @endif
</div>
</body>
</html>
