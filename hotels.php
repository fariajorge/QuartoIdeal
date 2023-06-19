<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page or any other appropriate location
  header("Location: login.php");
  exit();
}

require_once("DB/db_connection.php");
?>

<!DOCTYPE html>
<html>
<head>
    <link href="css/style.css" rel="stylesheet" />
    <title>Hotels - adiciona um hotel</title>
</head>
<body>
    <header>
        <nav>
          <ul>
          <li><a href="home.php">Home</a></li>
            <li><a href="#">Search Rooms</a></li>
            <li><a href="bookings.php">My Bookings</a></li>
            <li><a href="#">Contact</a></li>
            <li style="float:right"><a href="DB/db_logout.php">logout</a></li>
          </ul>
        </nav>
    </header>

    <h1>Create New Hotel</h1>
    <form action="DB/create_hotel.php" method="POST" enctype="multipart/form-data">
        <label for="name">Hotel Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>
        
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br><br>
        
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" required><br><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image"><br><br>
        
        <input type="submit" value="Create Hotel">
    </form>

</body>
</html>
