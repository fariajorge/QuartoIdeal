<!-- 
  Este arquivo PHP exclui um hotel e todos os seus quartos e reservas associadas do banco de dados 
  com base no ID do hotel fornecido na URL, redirecionando o utilizador de volta para a página inicial 
  após a conclusão das exclusões.
--> 

<?php
// Inclui o arquivo com a conexão com o banco de dados
require_once('db_connection.php');

// Obtém o ID do hotel a partir da requisição
$hotelId = $_GET['id'];

// Exclui as reservas associadas
$deleteBookingsQuery = "DELETE FROM bookings WHERE room_id IN (SELECT id FROM rooms WHERE hotel_id = $hotelId)";
$conn->query($deleteBookingsQuery);

// Exclui os quartos associados
$deleteRoomsQuery = "DELETE FROM rooms WHERE hotel_id = $hotelId";
$conn->query($deleteRoomsQuery);

// Exclui o hotel
$deleteHotelQuery = "DELETE FROM hotels WHERE id = $hotelId";
$conn->query($deleteHotelQuery);

// Redireciona de volta para a página inicial ou qualquer outra página apropriada
header("Location: ../home.php");
exit();
?>