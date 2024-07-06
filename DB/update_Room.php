<!--
  Este arquivo PHP atualiza os detalhes de um quarto de hotel no banco de dados, baseado nos dados 
  submetidos através de um formulário POST. Após a atualização bem-sucedida, redireciona o 
  utilizador de volta para a página de quartos do hotel específico. 
--> 

<?php
// Inclui o arquivo com a conexão com o banco de dados
require_once "db_connection.php";

// Verifica se os dados do formulário foram submetidos
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Recupera os dados do formulário
  $roomId = $_POST['room_id'];
  $hotelId=$_POST['hotel_id'];
  $roomNumber = $_POST['room_number'];
  $roomType = $_POST['room_type'];
  $description = $_POST['description'];
  $pricePerNight = $_POST['price_per_night'];

  // Atualiza o registro do quarto no banco de dados
  $sql = "UPDATE rooms SET room_number = '$roomNumber', room_type = '$roomType', description = '$description', price_per_night = '$pricePerNight' WHERE id = $roomId";

  // Executa a consulta SQL de atualização
  if ($conn->query($sql) === TRUE) {
    echo "Room updated successfully.";
  } else {
    echo "Error updating room: " . $conn->error;
  }
}

// Redireciona de volta para a página de quartos do hotel ou qualquer outra localização apropriada
header("Location: ../rooms.php?id=$hotelId");
exit();
?>