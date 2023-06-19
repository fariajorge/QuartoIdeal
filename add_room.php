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
if (!isset($_GET['id'])) {
  // Redirect the user to an appropriate location if the hotel ID is missing
  header("Location: home.php");
  exit();
}

$hotelId = $_GET['id'];

// Retrieve the hotel details from the database
$query = "SELECT * FROM hotels WHERE id = $hotelId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  // Fetch the hotel record
  $hotel = $result->fetch_assoc();

  // Fetch the hotel details
  $hotelName = $hotel['name'];
} else {
  // Redirect the user to an appropriate location if the hotel is not found
  header("Location: home.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="css/style.css" rel="stylesheet" />
    <title>Hotels - Create Room</title>
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

    <h1>Create Room</h1>
    <h2><?php echo $hotelName; ?></h2>

    <form action="DB/create_room.php" method="POST">
      <input type="hidden" name="hotel_id" value="<?php echo $hotelId; ?>">
      <label for="room_number">Room Number:</label>
      <input type="text" id="room_number" name="room_number" required><br><br>
      <label for="room_type">Room Type:</label>
      <input type="text" id="room_type" name="room_type" required><br><br>
      <label for="description">Description:</label>
      <textarea id="description" name="description" required></textarea><br><br>
      <label for="price_per_night">Price per Night:</label>
      <input type="number" id="price_per_night" name="price_per_night" required><br><br>
      <input type="submit" value="Create Room">
    </form>

</body>
</html>
