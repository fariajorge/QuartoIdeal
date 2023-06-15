<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page or any other appropriate location
  header("Location: login.php");
  exit();
}

require_once("db_connection.php");

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the form data
  $hotelId = $_POST['hotel_id'];
  $roomNumber = $_POST['room_number'];
  $roomType = $_POST['room_type'];
  $description = $_POST['description'];
  $pricePerNight = $_POST['price_per_night'];

  // Insert the new room into the database
  $sql = "INSERT INTO rooms (hotel_id, room_number, room_type, description, price_per_night) 
          VALUES ($hotelId, '$roomNumber', '$roomType', '$description', $pricePerNight)";

  if ($conn->query($sql) === TRUE) {
    echo "Room created successfully.";

    // Redirect to the hotel page
    header("Location: ../rooms.php?id=$hotelId");
    exit();
  } else {
    echo "Error creating room: " . $conn->error;
  }
}
?>
