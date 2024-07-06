<!--
  Este arquivo PHP processa a criação de reservas de quartos, verificando o login do utilizador,
  calculando o número de noites e inserindo os dados da reserva no banco de dados.
-->

<?php

//  inicia a sessão para poder acessar as variáveis de sessão
session_start();

// Verifica se o utilziador está logado
if (!isset($_SESSION['username'])) {
  // Redireciona o utlizador para a página de login ou qualquer outra localização apropriada
  header("Location: login.php");
  exit();
}

// Inclui o arquivo de conexão com o banco de dados
require_once("db_connection.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Recupera os dados do formulário
  $roomId = $_POST['room_id'];
  $entryDate = $_POST['entryDate'];
  $checkoutDate = $_POST['checkoutDate'];
  
  // Calcula o número de noites com base nas datas de entrada e saída
  $entryDateObj = new DateTime($entryDate);
  $checkoutDateObj = new DateTime($checkoutDate);
  $interval = $entryDateObj->diff($checkoutDateObj);
  $numNights = $interval->format('%a');

  // Insere a reserva no banco de dados
  $query = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, created_at)
            VALUES (?, ?, ?, ?, NOW())";
  $stmt = $conn->prepare($query);
  // Associa os parâmetros ao statement preparado
  $stmt->bind_param("iiss", $_SESSION['user_id'], $roomId, $entryDate, $checkoutDate);

  // Executa o statement e verifica se a reserva foi bem-sucedida
  if ($stmt->execute()) {
    // Reserva bem-sucedida
    header("Location: ../bookings.php");
    exit();
  } else {
    // Falha na reserva
    header("Location: ../home.php");
    exit();
  }
}
?>