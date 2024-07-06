<!-- 
  Este arquivo PHP exclui um quarto de hotel do banco de dados com base no ID do quarto fornecido 
  na URL, obtém o ID do hotel associado ao quarto antes de realizar a exclusão e redireciona o 
  utilizador de volta para a página de quartos do hotel após a exclusão.
-->

<?php
// Inclui o arquivo com a conexão com o banco de dados
require_once "db_connection.php";

// Verifica se o ID do quarto está fornecido na URL
if (isset($_GET['id'])) {
  $roomId = $_GET['id'];

  // Recupera o ID do hotel do banco de dados
  $sql = "SELECT hotel_id FROM rooms WHERE id = $roomId";
  $result = $conn->query($sql);

  // Verifica se o quarto foi encontrado no banco de dados
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hotelId = $row['hotel_id'];

    // Exclui o quarto do banco de dados
    $deleteSql = "DELETE FROM rooms WHERE id = $roomId";

    // Verifica se a exclusão foi bem-sucedida
    if ($conn->query($deleteSql) === TRUE) {
      echo "Room deleted successfully.";
    } else {
      echo "Error deleting room: " . $conn->error;
    }
  } else {
    echo "Room not found.";
  }
} else {
  echo "Room ID not provided.";
}

// Redireciona de volta para a página de quartos com o parâmetro do ID do hotel
header("Location: ../rooms.php?id=$hotelId");
exit();
?>