<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page or any other appropriate location
  header("Location: login.php");
  exit();
}

require_once("DB/db_connection.php");
// Check if the hotel ID is provided in the URL
if (isset($_GET['id'])) {
    $hotelId = $_GET['id'];
  
    // Retrieve the hotel data from the database
    $sql = "SELECT * FROM hotels WHERE id = $hotelId";
    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
    } else {
      echo "Hotel not found.";
      exit();
    }
  } else {
    echo "Hotel ID not provided.";
    exit();
  }
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
    <h1>Update Hotel</h1>
    <form action="DB/update_hotel.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="hotel_id" value="<?php echo $hotelId; ?>">
        <label for="name">Hotel Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $row['address']; ?>" required><br><br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="<?php echo $row['city']; ?>" required><br><br>
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" value="<?php echo $row['country']; ?>" required><br><br>
        <label for="image">Image:</label>
        <input type="file" id="image" name="image"><br><br>
        <input type="submit" value="Update Hotel">
    </form>

</body>
</html>
