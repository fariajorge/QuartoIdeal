<!-- 
  Este arquivo PHP processa a criação de novos quartos de hotel, verificando o login do utilizador,
  inserindo os dados no banco de dados e redirecionando para a página de quartos.
-->

<?php
session_start();

// Verifica se o utilizador está logado
if (!isset($_SESSION['username'])) {
  // Redireciona o utilizador para a página de login ou qualquer outra localização apropriada
  header("Location: login.php");
  exit();
}

// Inclui o arquivo de conexão com o banco de dados
require_once("db_connection.php");

// Verifica se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Recupera os dados do formulário
  $hotelId = $_POST['hotel_id'];
  $roomNumber = $_POST['room_number'];
  $roomType = $_POST['room_type'];
  $description = $_POST['description'];
  $pricePerNight = $_POST['price_per_night'];

  // Insere o novo quarto no banco de dados
  $sql = "INSERT INTO rooms (hotel_id, room_number, room_type, description, price_per_night) 
          VALUES ($hotelId, '$roomNumber', '$roomType', '$description', $pricePerNight)";

  // Verifica se a inserção foi bem-sucedida
  if ($conn->query($sql) === TRUE) {
    echo "Room created successfully.";

    // Redireciona para a página do hotel
    header("Location: ../rooms.php?id=$hotelId");
    exit();
  } else {
    // Exibe mensagem de erro caso a inserção falhe
    echo "Error creating room: " . $conn->error;
  }
}
?>