<!--
  Este arquivo PHP exibe uma lista de hotéis e permite que utilizadores com funções específicas 
  editem ou excluam hotéis. 
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

// Recupera os hotéis do banco de dados
$query = "SELECT * FROM hotels";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="css/styleHome.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <title></title>
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

<h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

<p>Bem-vindo ao nosso sistema de reservas de hotel!</p>
<p>Navegue pela nossa seleção de hotéis e faça a reserva da sua estadia perfeita. </p>
<p>Oferecemos uma ampla variedade de acomodações para atender às suas necessidades.</p> 
<p>Comece a explorar e faça a sua reserva hoje mesmo!</p>
<p></p>

<?php
if ($_SESSION['role'] == 'worker' || $_SESSION['role'] == 'admin') {
  echo '<a href="hotels.php"><button class="create-button">Create Hotel</button></a>';
}
?>

<p></p>
<p></p>

<div class="card-group">
<?php
// Verifica se há hotéis para exibir
if ($result->num_rows > 0) {
  // Loop através dos hotéis e exibe-os em cartões
  while ($row = $result->fetch_assoc()) {
    echo '
      <div class="card">
        <img class="card-img-top" src="data:image/jpeg;base64,' . $row['image'] . '" alt="Hotel Image">
        <div class="card-body">
            <h5 class="card-title">' . $row['name'] . '</h5>
            <p class="card-text">' . $row['address'] . '</p>
            <p class="card-text">' . $row['city'] . ', ' . $row['country'] . '</p>
            <div class="d-grid gap-2">
            <a href="rooms.php?id=' . $row['id'] . '"><button class="btn btn-success" type="button">ver quartos disponiveis</button></a>';

    // Mostra o botão de editar apenas para utilizadores com a função de worker ou admin
    if ($_SESSION['role'] == 'worker' || $_SESSION['role'] == 'admin') {
      echo '<a href="updateHotel.php?id=' . $row['id'] . '"><button class="btn btn-primary" type="button">Editar</button></a>';
    }
    // Mostra o botão de apagar apenas para utilizadores com a função de admin
    if ($_SESSION['role'] == 'admin') {
      echo '<a href="DB/delete_hotel.php?id=' . $row['id'] . '"><button class="btn btn-danger" type="button">Apagar</button></a>';
    }
    echo '</div>
        </div>
      </div>';
  }
}
else {
  echo "<p>No hotels found.</p>";
}



$conn->close();
?>
</div>

</body>
</html>
