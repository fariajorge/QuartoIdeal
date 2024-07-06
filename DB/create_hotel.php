<!-- 
  Este arquivo PHP cria novos registros de hotéis, incluindo uma imagem, inserindo os dados do 
  formulário no banco de dados e redirecionando o usuário para a página inicial.
-->

<?php
// Inclui o arquivo com a conexão ao banco de dados
require_once "db_connection.php";

// Recupera os dados do formulário
$name = $_POST['name'];
$address = $_POST['address'];
$city = $_POST['city'];
$country = $_POST['country'];

// Recupera o arquivo de imagem
$image = $_FILES['image']['tmp_name'];

// Verifica se um arquivo de imagem foi enviado
if (!empty($image)) {
    // Lê o arquivo de imagem
    $imageData = file_get_contents($image);

    // Codifica os dados da imagem em Base64
    $base64Image = base64_encode($imageData);

    // Insere os dados do hotel e a imagem no banco de dados
    $sql = "INSERT INTO hotels (name, address, city, country, image) VALUES ('$name', '$address', '$city', '$country', '$base64Image')";

    // Executa a consulta e verifica se foi bem-sucedida
    if ($conn->query($sql) === TRUE) {
        echo "Hotel created successfully.";
    } else {
        echo "Error creating hotel: " . $conn->error;
    }
} else {
    // Mensagem de erro caso nenhum arquivo de imagem tenha sido enviado
    echo "No image file uploaded.";
}

// Redireciona para a página inicial
header("Location: ../home.php");
exit();
?>