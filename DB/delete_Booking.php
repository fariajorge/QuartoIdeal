<?php
// Include the file with the database connection
require_once "db_connection.php";

// Check if the booking ID is provided in the URL
if (isset($_GET['id'])) {
  $bookingId = $_GET['id'];

  // Delete the booking from the database
  $sql = "DELETE FROM bookings WHERE id = $bookingId";

  if ($conn->query($sql) === TRUE) {
    echo "Booking deleted successfully.";
  } else {
    echo "Error deleting booking: " . $conn->error;
  }
} else {
  echo "Booking ID not provided.";
}

// Redirect back to the bookings page
header("Location: ../bookings.php");
exit();
?>
