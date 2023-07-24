<?php
require_once('db_connection.php');

// Get the hotel ID from the request
$hotelId = $_GET['id'];

// Delete associated bookings
$deleteBookingsQuery = "DELETE FROM bookings WHERE room_id IN (SELECT id FROM rooms WHERE hotel_id = $hotelId)";
$conn->query($deleteBookingsQuery);

// Delete associated rooms
$deleteRoomsQuery = "DELETE FROM rooms WHERE hotel_id = $hotelId";
$conn->query($deleteRoomsQuery);

// Delete the hotel
$deleteHotelQuery = "DELETE FROM hotels WHERE id = $hotelId";
$conn->query($deleteHotelQuery);

// Redirect back to the hotels page or any other appropriate location
header("Location: ../home.php");
exit();
?>
