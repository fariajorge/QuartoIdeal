<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page or any other appropriate location
  header("Location: login.php");
  exit();
}

require_once("DB/db_connection.php");

// Check if the hotel ID is provided in the URL
if (!isset($_GET['id'])) {
  // Redirect the user to an appropriate location if the hotel ID is missing
  header("Location: home.php");
  exit();
}

$hotelId = $_GET['id'];

// Retrieve the hotel details from the database
$query = "SELECT * FROM hotels WHERE id = $hotelId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  // Fetch the hotel record
  $hotel = $result->fetch_assoc();

  // Fetch the hotel details
  $hotelName = $hotel['name'];
  $address = $hotel['address'];
  $city = $hotel['city'];
  $country = $hotel['country'];
  $imageData = $hotel['image'];
} else {
  // Redirect the user to an appropriate location if the hotel is not found
  header("Location: home.php");
  exit();
}

// Retrieve the rooms of the hotel from the database
$query = "SELECT id, room_number, room_type, description, price_per_night FROM rooms WHERE hotel_id = $hotelId";
$result = $conn->query($query);
$rooms = [];

if ($result->num_rows > 0) {
  // Fetch all the rooms
  while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
  }
}
?>

<!DOCTYPE html>
<html>
<head>

<!-- style -->
  <link href="css/styleRoom.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

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

  <?php
    if ($_SESSION['role'] == 'worker' ||$_SESSION['role'] == 'admin') {
      echo "<a href='add_room.php?id=$hotelId'><button>Add Room</button></a>";
    }
  ?>
 <div class="hotel-details">
    <div class="hotel-image">
      <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" alt="Hotel Image">
    </div>
    <div class="hotel-info">
      <h1><?php echo $hotelName; ?></h1>
      <p>Address: <?php echo $address; ?></p>
      <p>City: <?php echo $city; ?></p>
      <p>Country: <?php echo $country; ?></p>
    </div>
  </div>


  
  
<h2>Rooms</h2>
  <?php
    // Check if there are rooms to display
    if (!empty($rooms)) {
      foreach ($rooms as $room) {
        echo '
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Room Number: ' . $room['room_number'] . '</h5>
            <p class="card-text">Room Type: ' . $room['room_type'] . '</p>
            <p class="card-text">Description: ' . $room['description'] . '</p>
            <p class="card-text">Price per Night: ' . $room['price_per_night'] . '</p>';

        // Display the "Reserve Room" button for all users
        echo '<button class="btn btn-success" type="button" data-toggle="modal" data-target="#reservationModal" data-room-id="' . $room['id'] . '" data-room-price="' . $room['price_per_night'] . '">Reserve Room</button>';

        // Show the "Edit" and "Delete" buttons for users with the worker or admin role
        if ($_SESSION['role'] == 'worker' || $_SESSION['role'] == 'admin') {
          echo '<a href="updateRoom.php?id=' . $room['id'] . '"><button class="btn btn-primary" type="button">Edit</button></a>';
        }
        if ($_SESSION['role'] == 'admin') {
          echo '<a href="DB/delete_room.php?id=' . $room['id'] . '"><button class="btn btn-danger" type="button">Delete</button></a>';
        }

        echo '
          </div>
        </div>';
      }
    } else {
      echo "<p>No rooms found for this hotel.</p>";
    }
    ?>




  <!-- Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reservationModalLabel">Room Reservation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="reservationForm" action="DB/book_room.php" method="post">
          <input type="hidden" name="room_id" id="room_id">
          <div class="form-group">
            <label for="entryDate">Entry Date</label>
            <input type="date" class="form-control" id="entryDate" name="entryDate" required>
          </div>
          <div class="form-group">
            <label for="checkoutDate">Check-out Date</label>
            <input type="date" class="form-control" id="checkoutDate" name="checkoutDate" required>
          </div>
          <div class="form-group">
            <label for="totalCost">Total Cost</label>
            <input type="text" class="form-control" id="totalCost" readonly>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" form="reservationForm">Reserve</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Handle modal show event
  $('#reservationModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var roomId = button.data('room-id'); // Extract room ID from data attribute
    var roomPrice = button.data('room-price'); // Extract room price from data attribute
    var modal = $(this);

    console.log(roomId+ "dafadafd");
    console.log(roomPrice+"fadsf");



    // Set room ID in the reservation form
    modal.find('#room_id').val(roomId);

    // Calculate and display the total cost based on the selected dates
    modal.find('#entryDate, #checkoutDate').on('input', function() {
      var entryDate = new Date(modal.find('#entryDate').val());
      var checkoutDate = new Date(modal.find('#checkoutDate').val());

      if (entryDate && checkoutDate && entryDate <= checkoutDate) {
        var timeDiff = Math.abs(checkoutDate.getTime() - entryDate.getTime());
        var numNights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        var totalCost = numNights * roomPrice;
        modal.find('#totalCost').val(totalCost);
      } else {
        modal.find('#totalCost').val('');
      }
    });
  });
</script>

</body>
</html>
