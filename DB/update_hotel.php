<?php
// Include the file with the database connection
require_once "db_connection.php";

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the form data
  $hotelId = $_POST['hotel_id'];
  $name = $_POST['name'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $country = $_POST['country'];

  // Check if a new image file is uploaded
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image = $_FILES['image']['tmp_name'];

    // Read the image file contents and encode it as base64
    $imageData = base64_encode(file_get_contents($image));

    // Update the hotel record with the new image in the database
    $sql = "UPDATE hotels SET name = '$name', address = '$address', city = '$city', country = '$country', image = '$imageData' WHERE id = $hotelId";
  } else {
    // Update the hotel record without changing the image in the database
    $sql = "UPDATE hotels SET name = '$name', address = '$address', city = '$city', country = '$country' WHERE id = $hotelId";
  }

  if ($conn->query($sql) === TRUE) {
    echo "Hotel updated successfully.";
  } else {
    echo "Error updating hotel: " . $conn->error;
  }
}

// Redirect back to the home page or any other appropriate location
header("Location: ../home.php");
exit();
?>
