<!-- 
  Este arquivo PHP permite atualizar os detalhes de um quarto de hotel específico, carregando os dados do quarto do 
  banco de dados com base no ID fornecido na URL.
--> 

<?php
session_start();

// Verifica se o utilizador está logado
if (!isset($_SESSION['username'])) {
    // Redireciona o utilizador para a página de login ou outra localização apropriada
    header("Location: login.php");
    exit();
}

require_once("DB/db_connection.php");

// Verifica se o ID do quarto foi fornecido na URL
if (isset($_GET['id'])) {
    $roomId = $_GET['id'];

    // Recupera os dados do quarto do banco de dados
    $sql = "SELECT * FROM rooms WHERE id = $roomId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $roomNumber = $row['room_number'];
        $roomType = $row['room_type'];
        $description = $row['description'];
        $pricePerNight = $row['price_per_night'];
    }
    else {
        echo "Room not found.";
        exit();
    }
}
else {
    echo "Room ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <<link href="css/styleAddRoom.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <title>Hotels - Update Room</title>
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
          <?php
}?>
          <li class="nav-item">
            <a class="nav-link" href="DB/db_logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>


    <h1>Update Room</h1>

    <form action="DB/update_room.php" method="post">
        <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
        <input type="hidden" name="hotel_id" value="<?php echo $row['hotel_id']; ?>">
        <div>
            <label for="roomNumber">Room Number</label>
            <input type="text" id="roomNumber" name="room_number" value="<?php echo $roomNumber; ?>" required>
        </div>
        <div>
            <label for="roomType">Room Type</label>
            <input type="text" id="roomType" name="room_type" value="<?php echo $roomType; ?>" required>
        </div>
        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?php echo $description; ?></textarea>
        </div>
        <div>
            <label for="pricePerNight">Price per Night</label>
            <input type="number" id="pricePerNight" name="price_per_night" value="<?php echo $pricePerNight; ?>" required>
        </div>
        <div>
            <button type="submit">Update</button>
        </div>
    </form>

</body>
</html>