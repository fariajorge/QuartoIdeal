<?php
// Include the file with the database connection
require_once "db_connection.php";

// Check if the room ID is provided in the URL
if (isset($_GET['id'])) {
  $roomId = $_GET['id'];

  // Retrieve the hotel ID from the database
  $sql = "SELECT hotel_id FROM rooms WHERE id = $roomId";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hotelId = $row['hotel_id'];

    // Delete the room from the database
    $deleteSql = "DELETE FROM rooms WHERE id = $roomId";

    if ($conn->query($deleteSql) === TRUE) {
      echo "Room deleted successfully.";
    } else {
      echo "Error deleting room: " . $conn->error;
    }
  } else {
    echo "Room not found.";
  }
} else {
  echo "Room ID not provided.";
}

// Redirect back to the home page with the hotel ID parameter
header("Location: ../rooms.php?id=$hotelId");
exit();
?>
