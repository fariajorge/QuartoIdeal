<!-- 
  Este arquivo PHP encerra a sessão do utilizador, limpando todas as variáveis de sessão e 
  redirecionando-o para a página de login.
-->

<?php
// Inclui o arquivo de conexão com o banco de dados
require_once "db_connection.php";

// Inicia a sessão
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = array();

// Destrói a sessão
session_destroy();

// Redireciona o utilizador para a página de login
header("Location: ../index.php");
exit();
?>