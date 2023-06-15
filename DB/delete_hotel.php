<?php
// Include the file with the database connection
require_once "db_connection.php";

// Check if the hotel ID is provided in the URL
if (isset($_GET['id'])) {
  $hotelId = $_GET['id'];

  // Delete the hotel from the database
  $sql = "DELETE FROM hotels WHERE id = $hotelId";

  if ($conn->query($sql) === TRUE) {
    echo "Hotel deleted successfully.";
  } else {
    echo "Error deleting hotel: " . $conn->error;
  }
} else {
  echo "Hotel ID not provided.";
}

// Redirect back to the home page Z
header("Location: ../home.php");
exit();
?>
