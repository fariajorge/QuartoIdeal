<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page or any other appropriate location
  header("Location: login.php");
  exit();
}

require_once("DB/db_connection.php");

// Check if the room ID is provided in the URL
if (isset($_GET['id'])) {
    $roomId = $_GET['id'];
  
    // Retrieve the room data from the database
    $sql = "SELECT * FROM rooms WHERE id = $roomId";
    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $roomNumber = $row['room_number'];
      $roomType = $row['room_type'];
      $description = $row['description'];
      $pricePerNight = $row['price_per_night'];
    } else {
      echo "Room not found.";
      exit();
    }
} else {
    echo "Room ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="css/style.css" rel="stylesheet" />
    <title>Hotels - Update Room</title>
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

    <h1>Update Room</h1>

    <form action="DB/update_room.php" method="post">
        <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
        <input type="hidden" name="hotel_id" value="<?php echo $row['hotel_id']; ?>">
        <div>
            <label for="roomNumber">Room Number</label>
            <input type="text" id="roomNumber" name="room_number" value="<?php echo $roomNumber; ?>" required>
        </div>
        <div>
            <label for="roomType">Room Type</label>
            <input type="text" id="roomType" name="room_type" value="<?php echo $roomType; ?>" required>
        </div>
        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?php echo $description; ?></textarea>
        </div>
        <div>
            <label for="pricePerNight">Price per Night</label>
            <input type="number" id="pricePerNight" name="price_per_night" value="<?php echo $pricePerNight; ?>" required>
        </div>
        <div>
            <button type="submit">Update</button>
        </div>
    </form>

</body>
</html>
