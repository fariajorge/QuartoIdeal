<?php

require_once "db_connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate the inputs (perform necessary sanitization as well)
  if (empty($username) || empty($password) || empty($email)) {
    // Display an error message if any field is empty
    $error = "Please enter all required fields.";
  } else {
    // Check if the username or email already exists in the database
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Display an error message if the username or email is already taken
      $error = "Username or email already exists.";
    } else {
      // Hash the password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Insert the user's information into the database
      $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sss", $username, $hashedPassword, $email);
      $stmt->execute();

      // Redirect the user to the login page after successful registration
      header("Location: ../login.php");
      exit();
    }
  }
}

// HTML content for the registration page
?>