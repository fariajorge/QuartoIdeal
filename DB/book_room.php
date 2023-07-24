<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page or any other appropriate location
  header("Location: login.php");
  exit();
}

require_once("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the form data
  $roomId = $_POST['room_id'];
  $entryDate = $_POST['entryDate'];
  $checkoutDate = $_POST['checkoutDate'];
  
  // Calculate the number of nights based on the entry and checkout dates
  $entryDateObj = new DateTime($entryDate);
  $checkoutDateObj = new DateTime($checkoutDate);
  $interval = $entryDateObj->diff($checkoutDateObj);
  $numNights = $interval->format('%a');

  // Insert the reservation into the database
  $query = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, created_at)
            VALUES (?, ?, ?, ?, NOW())";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("iiss", $_SESSION['user_id'], $roomId, $entryDate, $checkoutDate);

  if ($stmt->execute()) {
    // Reservation successful
    header("Location: ../bookings.php");
    exit();
  } else {
    // Reservation failed
    header("Location: ../home.php");
    exit();
  }
}
?>
