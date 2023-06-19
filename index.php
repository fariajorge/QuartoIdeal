<?php
require_once("DB/db_connection.php");

// Retrieve hotels from the database
$query = "SELECT * FROM hotels";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <title>Quarto Ideal</title>
</head>
<body>

    <header>
        <nav>
          <ul>
            <li><a href="index.php">Home</a></li>
            <li style="float:right"><a href="Register.php">Register</a></li>
            <li style="float:right"><a href="logIn.php">Login</a></li>
          </ul>
        </nav>
    </header>

  <main>
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
  </main>

  <footer>
    <p>&copy; 2023 Hotel Room Reservation System. All rights reserved.</p>
  </footer>
</body>
</html>
