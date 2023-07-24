<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/styleLogIn.css" rel="stylesheet" />
  <title>Login</title>
</head>
<body>

    <header>
        <nav>
          <ul>
          <li><a href="index.php">Home</a></li>
            <li style="float:right"><a href="Register.php">Register</a></li>
            <li style="float:right"><a href="logIn.php">Login</a></li>
          </ul>
        </nav>
    </header>

  <h1 style="color:white">Login</h1>

  <form action="DB/db_login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Login">
  </form>

  <footer>
    <p>&copy; 2023 Hotel Room Reservation System. All rights reserved.</p>
  </footer>
</body>
</html>
