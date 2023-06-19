<?php
// Include the file with the database connection
require_once "db_connection.php";

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the form data
  $roomId = $_POST['room_id'];
  $hotelId=$_POST['hotel_id'];
  $roomNumber = $_POST['room_number'];
  $roomType = $_POST['room_type'];
  $description = $_POST['description'];
  $pricePerNight = $_POST['price_per_night'];

  // Update the room record in the database
  $sql = "UPDATE rooms SET room_number = '$roomNumber', room_type = '$roomType', description = '$description', price_per_night = '$pricePerNight' WHERE id = $roomId";

  if ($conn->query($sql) === TRUE) {
    echo "Room updated successfully.";
  } else {
    echo "Error updating room: " . $conn->error;
  }
}

// Redirect back to the home page or any other appropriate location
header("Location: ../rooms.php?id=$hotelId");
exit();
?>
