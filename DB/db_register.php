<?php
require_once "db_connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];
  $role = $_POST["role"];

  // Validate the inputs (perform necessary sanitization as well)
  if (empty($username) || empty($password) || empty($email) || empty($role)) {
    // Display an error message if any field is empty
    $error = "Please enter username, password, email, and role.";
  } else {
    // Check if the username is already taken
    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Display an error message if the username is already taken
      $error = "Username already exists. Please choose a different username.";
    } else {
      // Hash the password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Insert the user into the database
      $query = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ssss", $username, $hashedPassword, $email, $role);
      $stmt->execute();

      // Start a session and store user information in session variables
      session_start();
      $_SESSION["user_id"] = $stmt->insert_id;
      $_SESSION["username"] = $username;
      $_SESSION["role"] = $role;

      // Redirect the user to the desired page after successful registration
      header("Location: ../home.php");
      exit();
    }
  }
}

// HTML content for the register page
?>
