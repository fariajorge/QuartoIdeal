<!--
  Este arquivo PHP exclui uma reserva de hotel do banco de dados com base no ID fornecido na URL e 
  redireciona o utilizador de volta para a página de reservas.
-->

<?php
// Inclui o arquivo com a conexão com o banco de dados
require_once "db_connection.php";

// Verifica se o ID da reserva está fornecido na URL
if (isset($_GET['id'])) {
  $bookingId = $_GET['id'];

  // Exclui a reserva do banco de dados
  $sql = "DELETE FROM bookings WHERE id = $bookingId";

  // Verifica se a exclusão foi bem-sucedida
  if ($conn->query($sql) === TRUE) {
    echo "Booking deleted successfully.";
  } else {
    echo "Error deleting booking: " . $conn->error;
  }
} else {
  echo "Booking ID not provided.";
}

// Redireciona de volta para a página de reservas
header("Location: ../bookings.php");
exit();
?>