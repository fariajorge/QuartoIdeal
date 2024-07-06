<!--
  Este arquivo PHP exibe as reservas do utilizador autenticado e permite que utilizadores com 
  funções específicas editem ou excluam reservas. 
--> 

<?php
session_start();

// Verifica se o utilizador está logado
if (!isset($_SESSION['username'])) {
  // Redireciona o utilizador para a página de login ou outra localização apropriada
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

  <!-- Option 1: Include in HTML -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

<title>Hotel Details</title>
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
      // Inclui o arquivo com a conexão ao banco de dados
      require_once "DB/db_connection.php";

      // Obtém o ID do utilizador da sessão
      $userId = $_SESSION['user_id'];

      // Recupera as reservas para o utilizador logado do banco de dados
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
          
          // Modal de edição para cada reserva
          // Cria um modal com um identificador único (id) baseado no bookingId da reserva
          echo "<div class='modal fade' id='editModal$bookingId' tabindex='-1' role='dialog' aria-labelledby='editModalLabel$bookingId' aria-hidden='true'>";
          
          // Define a estrutura do modal, incluindo o conteúdo e o diálogo.
          echo "<div class='modal-dialog' role='document'>";
          echo "<div class='modal-content'>";

          // Cria o cabeçalho do modal com um título e um botão para fechar o modal.
          echo "<div class='modal-header'>";
          echo "<h5 class='modal-title' id='editModalLabel$bookingId'>Edit Booking</h5>";
          echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
          echo "<span aria-hidden='true'>&times;</span>";
          echo "</button>";
          echo "</div>";

          // O corpo do modal contém um formulário com campos para editar as datas de check-in e check-out.
          // Os campos são pré-preenchidos com os valores atuais
          // O formulário é enviado para DB/update_booking.php usando o método POST.
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

          // O rodapé do modal inclui um botão para fechar o modal.
          echo "<div class='modal-footer'>";
          echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
        }

        // Se não houver reservas, uma mensagem indicando que nenhuma reserva foi encontrada é exibida.
      } else {
        echo "<tr><td colspan='5'>No bookings found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
  </div>
</body>
</html>
