<!-- 
  Este arquivo PHP gerencia a atualização de detalhes de uma reserva de hotel no banco de dados. Ele 
  verifica se o utilizador está autenticado, recupera os detalhes da reserva com base no ID fornecido 
  na URL, permite a atualização das datas de check-in e check-out através de um formulário POST e 
  redireciona o utilizador de volta para a página de reservas após a conclusão da atualização.
--> 

<?php
//  inicia a sessão para poder acessar as variáveis de sessão
session_start();

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['username'])) {
  // Redireciona o utilizador para a página de login ou outra localização apropriada
  header("Location: login.php");
  exit();
}

require_once("db_connection.php");

// Verifica se o ID da reserva está fornecido na URL
if (!isset($_GET['id'])) {
  // Redireciona o utilizador para uma localização apropriada se o ID da reserva estiver em falta
  header("Location: bookings.php");
  exit();
}

$bookingId = $_GET['id'];

// Recupera os detalhes da reserva do banco de dados
$query = "SELECT * FROM bookings WHERE id = $bookingId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  // Busca o registro da reserva
  $booking = $result->fetch_assoc();

  // Recupera os detalhes da reserva
  $roomId = $booking['room_id'];
  $checkInDate = $booking['check_in_date'];
  $checkOutDate = $booking['check_out_date'];
} else {
  // Redireciona o utilizador para uma localização apropriada se a reserva não for encontrada
  header("Location: ../bookings.php");
  exit();
}

// Manipula a submissão do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Recupera as novas datas de check-in e check-out do formulário
  $newCheckInDate = $_POST['check_in_date'];
  $newCheckOutDate = $_POST['check_out_date'];

  // Atualiza a reserva no banco de dados
  $updateQuery = "UPDATE bookings SET check_in_date = '$newCheckInDate', check_out_date = '$newCheckOutDate' WHERE id = $bookingId";
  if ($conn->query($updateQuery) === TRUE) {
    // Redireciona o utilizador para a página de reservas após a atualização bem-sucedida
    header("Location: ../bookings.php");
    exit();
  } else {
    // Manipula o caso de erro
    echo "Error updating booking: " . $conn->error;
  }
}
?>