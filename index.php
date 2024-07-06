<!-- 
  Este arquivo PHP exibe a página inicial do site, listando os hotéis disponíveis com suas 
  informações e opções para visualizar os quartos disponíveis.
--> 

<?php
require_once("DB/db_connection.php");

// Recupera hotéis do banco de dados
$query = "SELECT * FROM hotels";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/styleIndex.css" rel="stylesheet" />
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

  <div class="description-box">
      <h1>Bem vindo ao Quarto Ideal!</h1>
      <p>Descubra o hotel perfeito para a sua próxima viagem.</p>
  </div>

  <div class="card-group">

<?php
// Verifica se há hotéis para exibir
if ($result->num_rows > 0) {
    // Itera pelos hotéis e os exibe em cartões
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
    <p>&copy; 2024 Hotel Room Reservation System. All rights reserved.</p>
  </footer>
</body>
</html>
