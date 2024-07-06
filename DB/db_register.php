<!-- 
  Este arquivo PHP processa o registro de novos usuários em um sistema, validando os dados do 
  formulário, verificando a disponibilidade do nome de usuário, criptografando a senha, inserindo os 
  dados no banco de dados e iniciando uma sessão para o usuário registrado.
-->

<?php
// Inclui o arquivo de conexão com o banco de dados
require_once "db_connection.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recupera os dados do formulário
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];
  $role = 'client';

  // Valida as entradas (realiza a sanitização necessária também)
  if (empty($username) || empty($password) || empty($email) ) {
    // Exibe uma mensagem de erro se algum campo estiver vazio
    $error = "Please enter username, password, email";
  } else {
    // Verifica se o nome de usuário já está em uso
    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Exibe uma mensagem de erro se o nome de usuário já estiver em uso
      $error = "Username already exists. Please choose a different username.";
    } else {
      // Criptografa a senha
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Insere o utilizador no banco de dados
      $query = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ssss", $username, $hashedPassword, $email, $role);
      $stmt->execute();

      // Inicia uma sessão e armazena informações do usuário em variáveis de sessão
      session_start();
      $_SESSION["user_id"] = $stmt->insert_id; // Obtém o ID do usuário recém-inserido
      $_SESSION["username"] = $username;
      $_SESSION["role"] = $role;

      // Redireciona o usuário para a página desejada após o registro bem-sucedido
      header("Location: ../home.php");
      exit();
    }
  }
}

// HTML content for the register page
?>