<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page or any other appropriate location
  header("Location: login.php");
  exit();
}

require_once("DB/db_connection.php");

// Retrieve hotels from the database
$query = "SELECT * FROM hotels";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <title></title>
</head>

<body>

<header>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="#">Search Rooms</a></li>
            <li><a href="bookings.php">My Bookings</a></li>
            <li><a href="#">Contact</a></li>
            <li style="float:right"><a href="DB/db_logout.php">Logout</a></li>           
        </ul>
    </nav>
</header>

<h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

<a href="hotels.php"><button>Create Hotel</button></a>

<div class="card-group">
<?php
// Check if there are hotels to display
if ($result->num_rows > 0) {
    // Loop through the hotels and display them in cards
    while ($row = $result->fetch_assoc()) {
      echo '
      <div class="card">
        <img class="card-img-top" src="data:image/jpeg;base64,' . $row['image'] . '" alt="Hotel Image">
        <div class="card-body">
            <h5 class="card-title">' . $row['name'] . '</h5>
            <p class="card-text">' . $row['address'] . '</p>
            <p class="card-text">' . $row['city'] . ', ' . $row['country'] . '</p>
            <div class="d-grid gap-2">
            <a href="rooms.php?id=' . $row['id'] . '"><button class="btn btn-success" type="button">ver quartos disponiveis</button></a>
            <a href="updateHotel.php?id=' . $row['id'] . '"><button class="btn btn-primary" type="button">Editar</button></a>
            <a href="DB/delete_hotel.php?id=' . $row['id'] . '"><button class="btn btn-danger" type="button">Apagar</button></a>
            </div>
        </div>
      </div>';
    }
  } else {
    echo "<p>No hotels found.</p>";
  }
  

$conn->close();
?>
</div>

</body>
</html>
