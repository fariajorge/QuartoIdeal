<!-- create_hotel.php -->
<?php
// Include the file with the database connection
require_once "db_connection.php";

// Retrieve the form data
$name = $_POST['name'];
$address = $_POST['address'];
$city = $_POST['city'];
$country = $_POST['country'];

// Retrieve the image file
$image = $_FILES['image']['tmp_name'];

// Check if an image file was uploaded
if (!empty($image)) {
    // Read the image file
    $imageData = file_get_contents($image);

    // Encode the image data into Base64
    $base64Image = base64_encode($imageData);

    // Insert the hotel data and image into the database
    $sql = "INSERT INTO hotels (name, address, city, country, image) VALUES ('$name', '$address', '$city', '$country', '$base64Image')";

    if ($conn->query($sql) === TRUE) {
        echo "Hotel created successfully.";
    } else {
        echo "Error creating hotel: " . $conn->error;
    }
} else {
    echo "No image file uploaded.";
}

header("Location: ../home.php");
exit();
?>
