<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page or any other appropriate location
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
 <!-- style -->
 <link href="css/styleBooking.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
<!-- Option 1: Include in HTML -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <title>Hotel Details</title>
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="bookings.php">My Bookings</a></li>
        <li style="float:right"><a href="DB/db_logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <h1>My Bookings</h1>

  <div class="booking-container">
    <table class="booking-table">
    <thead>
      <tr>
        <th>Booking ID</th>
        <th>Room ID</th>
        <th>Room Number</th>
        <th>Room Type</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
      // Include the file with the database connection
      require_once "DB/db_connection.php";

      // Get the user ID from the session
      $userId = $_SESSION['user_id'];

      // Retrieve the bookings for the logged-in user from the database
      $sql = "SELECT b.id, r.room_number, b.check_in_date, b.check_out_date FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = $userId";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $bookingId = $row['id'];
          $roomNumber = $row['room_number'];
          $checkInDate = $row['check_in_date'];
          $checkOutDate = $row['check_out_date'];

          echo "<tr>";
          echo "<td>$bookingId</td>";
          echo "<td>$roomNumber</td>";
          echo "<td>$checkInDate</td>";
          echo "<td>$checkOutDate</td>";
          echo "<td>";          
          if ($_SESSION['role'] == 'worker' || $_SESSION['role'] == 'admin') {
            echo "<a href='#' data-toggle='modal' data-target='#editModal$bookingId' class='btn btn-primary'><i class='bi bi-pencil-fill'></i></a>";
          }          
          if ($_SESSION['role'] == 'admin') {
            echo "<a href='DB/delete_booking.php?id=$bookingId' class='btn btn-danger'><i class='bi bi-trash-fill'></i></a>";
          }
          echo "</td>";
          echo "</tr>";
          
          // Edit Modal for each booking
          echo "<div class='modal fade' id='editModal$bookingId' tabindex='-1' role='dialog' aria-labelledby='editModalLabel$bookingId' aria-hidden='true'>";
          echo "<div class='modal-dialog' role='document'>";
          echo "<div class='modal-content'>";
          echo "<div class='modal-header'>";
          echo "<h5 class='modal-title' id='editModalLabel$bookingId'>Edit Booking</h5>";
          echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
          echo "<span aria-hidden='true'>&times;</span>";
          echo "</button>";
          echo "</div>";
          echo "<div class='modal-body'>";
          echo "<form action='DB/update_booking.php' method='POST'>";
          echo "<input type='hidden' name='booking_id' value='$bookingId'>";
          echo "<div class='form-group'>";
          echo "<label for='checkInDate'>Check-in Date:</label>";
          echo "<input type='date' class='form-control' id='checkInDate' name='check_in_date' value='$checkInDate'>";
          echo "</div>";
          echo "<div class='form-group'>";
          echo "<label for='checkOutDate'>Check-out Date:</label>";
          echo "<input type='date' class='form-control' id='checkOutDate' name='check_out_date' value='$checkOutDate'>";
          echo "</div>";
          echo "<button type='submit' class='btn btn-primary'>Save Changes</button>";
          echo "</form>";
          echo "</div>";
          echo "<div class='modal-footer'>";
          echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";

        }
      } else {
        echo "<tr><td colspan='5'>No bookings found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
  </div>
</body>
</html>
