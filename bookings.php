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
  <title>Hotel Details</title>
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="#">Search Rooms</a></li>
        <li><a href="bookings.php">My Bookings</a></li>
        <li><a href="#">Contact</a></li>
        <li style="float:right"><a href="DB/db_logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>
  
  

 


</body>
</html>
