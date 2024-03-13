<!DOCTYPE html>
<html>
<head>
    <title>Welcome to ItSolutionStuff.com - </title>
</head>
<body>
    <h1>Welcome, {{ $UserName }}</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
  
    <br/>
    <strong>Profile Picture:</strong><br/>
    <img src="{{storage_path('app/public/'.$ProfilePic) }}" style="width: 200px; height: 200px">
</body>
</html>
