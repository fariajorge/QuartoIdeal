<?php
session_start();

// Verifica se o utilizador é um admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  // Redireciona o utilizador para a página de login ou outra localização apropriada
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <!-- style -->
  <link href="css/styleAllBookings.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
  <!-- Option 1: Include in HTML -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <title>Admin View</title>
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="home.php">Home</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="bookings.php">My Bookings</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <?php if ($_SESSION['role'] == 'admin') { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="adminMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Admin Menu
            </a>
            <div class="dropdown-menu" aria-labelledby="adminMenu">
              <a class="dropdown-item" href="adminView.php">All Bookings</a>
              <a class="dropdown-item" href="allUsers.php">Users</a>
            </div>
          </li>
          <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="DB/db_logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <h1>All Bookings</h1>

  <div class="booking-container">
    <table class="booking-table">
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>User Email</th>
          <th>Room Number</th>
          <th>Check-in Date</th>
          <th>Check-out Date</th>
        </tr>
      </thead>
      <tbody>
      <?php
        // Inclui o arquivo com a conexão ao banco de dados
        require_once "DB/db_connection.php";

        // Recupera todas as reservas do banco de dados
        $sql = "SELECT b.id, u.email, r.room_number, b.check_in_date, b.check_out_date 
                FROM bookings b 
                JOIN rooms r ON b.room_id = r.id 
                JOIN users u ON b.user_id = u.id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $bookingId = $row['id'];
            $userEmail = $row['email'];
            $roomNumber = $row['room_number'];
            $checkInDate = $row['check_in_date'];
            $checkOutDate = $row['check_out_date'];

            echo "<tr>";
            echo "<td>$bookingId</td>";
            echo "<td>$userEmail</td>";
            echo "<td>$roomNumber</td>";
            echo "<td>$checkInDate</td>";
            echo "<td>$checkOutDate</td>";
            echo "</tr>";
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
