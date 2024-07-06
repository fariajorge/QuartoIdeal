<!-- 
  Este arquivo PHP processa a autenticação de usuários através de um formulário de login, verificando 
  as credenciais no banco de dados e iniciando uma sessão se as credenciais forem válidas.
-->

<?php
// Inclui o arquivo de conexão com o banco de dados
require_once "db_connection.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recupera os dados do formulário
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Valida as entradas (realiza a sanitização necessária também)
  if (empty($username) || empty($password)) {
    // Exibe uma mensagem de erro se algum campo estiver vazio
    $error = "Please enter both username and password.";
  } else {
    // Consulta o banco de dados para verificar se as credenciais fornecidas correspondem a um utilizador
    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();

      // Verifica a senha
      if (password_verify($password, $row["password"])) {
        // Inicia uma sessão e armazena as informações do utilizador em variáveis de sessão
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        $_SESSION["role"] = $row["role"]; // Supondo que 'role' seja uma coluna na tabela 'users'
        var_dump("akshdaksf");

        // Redireciona o utilizador para a página desejada após o login bem-sucedido
        header("Location: ../home.php");
        exit();
      } else {
        // Exibe uma mensagem de erro se a senha estiver incorreta
        $error = "Password inválida.";
        var_dump($error); // (é usada para exibir informações detalhadas sobre uma variável ou expressão em PHP)
      }
    } else {
      // Exibe uma mensagem de erro se o nome de usuário não for encontrado
      $error = "Nome do utilizador inválido.";
      var_dump($error);
    }
  }
}

// HTML content for the login page
?>