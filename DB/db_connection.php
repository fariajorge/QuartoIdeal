<!--
  Este arquivo PHP configura e estabelece uma conexão com o banco de dados de hotéis.
--> 
 
<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotels";

// Cria uma conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
  // Se a conexão falhar, exibe uma mensagem de erro e termina o script
  die("Connection failed: " . $conn->connect_error);
}
?>