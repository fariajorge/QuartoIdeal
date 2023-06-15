<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/style.css" rel="stylesheet" />
  <title>Register</title>
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

  <h1>Register</h1>

  <form action="DB/db_register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="userType">User Type:</label>
<select id="userType" name="userType" required>
  <option value="admin">Admin</option>
  <option value="client">Client</option>
  <option value="worker">Worker</option>
</select>


    <input type="submit" value="Register">
  </form>
</body>
</html>
