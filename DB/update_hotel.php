<!-- 
  Este arquivo PHP atualiza os detalhes de um hotel no banco de dados, incluindo a possibilidade de 
  atualizar a imagem do hotel se um novo arquivo de imagem for enviado através de um formulário 
  POST. Após a conclusão da atualização, redireciona o utilizador de volta para a página inicial.
--> 

<?php
// Inclui o arquivo com a conexão com o banco de dados
require_once "db_connection.php";

// Verifica se os dados do formulário foram submetidos
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Recupera os dados do formulário
  $hotelId = $_POST['hotel_id'];
  $name = $_POST['name'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $country = $_POST['country'];

  // Verifica se um novo arquivo de imagem foi carregado
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image = $_FILES['image']['tmp_name'];

    // Lê o conteúdo do arquivo de imagem e codifica como base64
    $imageData = base64_encode(file_get_contents($image));

    // Atualiza o registro do hotel com a nova imagem no banco de dados
    $sql = "UPDATE hotels SET name = '$name', address = '$address', city = '$city', country = '$country', image = '$imageData' WHERE id = $hotelId";
  } else {
    // Atualiza o registro do hotel sem alterar a imagem no banco de dados
    $sql = "UPDATE hotels SET name = '$name', address = '$address', city = '$city', country = '$country' WHERE id = $hotelId";
  }

  // Executa a consulta SQL de atualização
  if ($conn->query($sql) === TRUE) {
    echo "Hotel updated successfully.";
  } else {
    echo "Error updating hotel: " . $conn->error;
  }
}

// Redireciona de volta para a página inicial ou qualquer outra localização apropriada
header("Location: ../home.php");
exit();
?>