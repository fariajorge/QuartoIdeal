<?php
require_once "db_connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the inputs (perform necessary sanitization as well)
  if (empty($username) || empty($password)) {
    // Display an error message if any field is empty
    $error = "Please enter both username and password.";
  } else {
    // Query the database to check if the provided credentials match
    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Start a session and store user information in session variables
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];

        // Redirect the user to the desired page after successful login
        header("Location: ../home.php");
        exit();
      } else {
        // Display an error message if the password is incorrect
        $error = "Invalid password.";
      }
    } else {
      // Display an error message if the username is not found
      $error = "Invalid username.";
    }
  }
}

// HTML content for the login page
?>