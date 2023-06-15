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
  $address = $hotel['address'];
  $city = $hotel['city'];
  $country = $hotel['country'];
  $imageData = $hotel['image'];
} else {
  // Redirect the user to an appropriate location if the hotel is not found
  header("Location: home.php");
  exit();
}

// Retrieve the rooms of the hotel from the database
$query = "SELECT id, room_number, room_type, description, price_per_night FROM rooms WHERE hotel_id = $hotelId";
$result = $conn->query($query);
$rooms = [];

if ($result->num_rows > 0) {
  // Fetch all the rooms
  while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
  }
}
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
        <li><a href="#">My Bookings</a></li>
        <li><a href="#">Contact</a></li>
        <li style="float:right"><a href="DB/db_logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>
  <a href="add_room.php?id=<?php echo $hotelId; ?>"><button>Add Room</button></a>
  <h1>Hotel Details</h1>

  <h2><?php echo $hotelName; ?></h2>
  <p>Address: <?php echo $address; ?></p>
  <p>City: <?php echo $city; ?></p>
  <p>Country: <?php echo $country; ?></p>
  <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" alt="Hotel Image" width="300">
  

  <h2>Rooms</h2>
  <?php if (count($rooms) > 0) : ?>
    <ul>
      <?php foreach ($rooms as $room) : ?>
        <li>
            Room Number: <?php echo $room['room_number']; ?><br>
            Room Type: <?php echo $room['room_type']; ?><br>
            Description: <?php echo $room['description']; ?><br>
            Price per Night: <?php echo $room['price_per_night']; ?><br>
            <a href="rooms.php?id=<?php echo $room['id']; ?>"><button class="btn btn-success" type="button">Reservar quarto</button></a>
            <a href="updateRoom.php?id=<?php echo $room['id']; ?>"><button class="btn btn-primary" type="button">Edit</button></a>
            <a href="DB/delete_room.php?id=<?php echo $room['id']; ?>"><button class="btn btn-danger" type="button">Delete</button></a>
        </li>

      <?php endforeach; ?>
    </ul>
  <?php else : ?>
    <p>No rooms found for this hotel.</p>
  <?php endif; ?>


</body>
</html>
