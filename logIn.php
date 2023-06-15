<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/style.css" rel="stylesheet" />
  <title>Login</title>
</head>
<body>

    <header>
        <nav>
          <ul>
          <li><a href="index.php">Home</a></li>
            <li><a href="#">Search Rooms</a></li>
            <li><a href="#">My Bookings</a></li>
            <li><a href="#">Contact</a></li>
            <li style="float:right"><a href="Register.php">Register</a></li>
            <li style="float:right"><a href="logIn.php">Login</a></li>
          </ul>
        </nav>
    </header>

  <h1>Login</h1>

  <form action="DB/db_login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Login">
  </form>
</body>
</html>
