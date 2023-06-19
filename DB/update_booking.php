<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page or any other appropriate location
  header("Location: login.php");
  exit();
}

require_once("db_connection.php");

// Check if the booking ID is provided in the URL
if (!isset($_GET['id'])) {
  // Redirect the user to an appropriate location if the booking ID is missing
  header("Location: bookings.php");
  exit();
}

$bookingId = $_GET['id'];

// Retrieve the booking details from the database
$query = "SELECT * FROM bookings WHERE id = $bookingId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  // Fetch the booking record
  $booking = $result->fetch_assoc();

  // Fetch the booking details
  $roomId = $booking['room_id'];
  $checkInDate = $booking['check_in_date'];
  $checkOutDate = $booking['check_out_date'];
} else {
  // Redirect the user to an appropriate location if the booking is not found
  header("Location: ../bookings.php");
  exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the updated check-in and check-out dates from the form
  $newCheckInDate = $_POST['check_in_date'];
  $newCheckOutDate = $_POST['check_out_date'];

  // Update the booking in the database
  $updateQuery = "UPDATE bookings SET check_in_date = '$newCheckInDate', check_out_date = '$newCheckOutDate' WHERE id = $bookingId";
  if ($conn->query($updateQuery) === TRUE) {
    // Redirect the user to the bookings page after successful update
    header("Location: ../bookings.php");
    exit();
  } else {
    // Handle the error case
    echo "Error updating booking: " . $conn->error;
  }
}
?>
