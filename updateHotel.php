<!--
  Este arquivo exibe um formulário para atualizar os detalhes de um hotel existente, carregando 
  os dados do hotel a partir do banco de dados. 
--> 

<?php
session_start();

// Verifica se o utilizador está logado
if (!isset($_SESSION['username'])) {
  // Redireciona o utilizador para a página de login ou outra localização apropriada
  header("Location: login.php");
  exit();
}

require_once("DB/db_connection.php");

// Verifica se o ID do hotel foi fornecido na URL
if (isset($_GET['id'])) {
    $hotelId = $_GET['id'];
  
    // Recupera os dados do hotel do banco de dados
    $sql = "SELECT * FROM hotels WHERE id = $hotelId";
    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
    } else {
      echo "Hotel not found.";
      exit();
    }
  } else {
    echo "Hotel ID not provided.";
    exit();
  }
  ?>

<!DOCTYPE html>
<html>
<head>
<link href="css/styleHotels.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <title>Hotels - adiciona um hotel</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="home.php">Home</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="bookings.php">My Bookings</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <?php if ($_SESSION['role'] == 'admin') { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="adminMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Admin Menu
            </a>
            <div class="dropdown-menu" aria-labelledby="adminMenu">
              <a class="dropdown-item" href="adminView.php">All Bookings</a>
              <a class="dropdown-item" href="allUsers.php">Users</a>
            </div>
          </li>
          <?php
}?>
          <li class="nav-item">
            <a class="nav-link" href="DB/db_logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>


    <h1>Create New Hotel</h1>
    <p></p>
    <p></p>

    <h2>Update Hotel</h2>
    <p></p>

    <form action="DB/update_hotel.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="hotel_id" value="<?php echo $hotelId; ?>">
        <label for="name">Hotel Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required><br><br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $row['address']; ?>" required><br><br>
        
        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="<?php echo $row['city']; ?>" required><br><br>
        
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" value="<?php echo $row['country']; ?>" required><br><br>
        
        <!-- Campos para armazenar latitude e longitude -->
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <!-- Mapa do Google Maps -->
        <div id="map" style="height: 400px; width: 100%;"></div><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image"><br><br>

        <p></p>

        <input type="submit" value="Update Hotel">
    </form>

    <script>
      // Função para inicializar o mapa do Google Maps
      function initMap() {
          // Verificar se o navegador suporta geolocalização
          if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                  var userLatLng = {
                      lat: position.coords.latitude,
                      lng: position.coords.longitude
                  };

                  // Imprime as coordenadas obtidas
                  console.log('Latitude:', userLatLng.lat);
                  console.log('Longitude:', userLatLng.lng);

                  var map = new google.maps.Map(document.getElementById('map'), {
                      center: userLatLng, // Centrar o mapa na posição do utilizador
                      zoom: 15 // Zoom inicial
                  });

                  var map = new google.maps.Map(document.getElementById('map'), {
                      center: userLatLng, // Centrar o mapa na posição do utilizador
                      zoom: 15 // Zoom inicial
                  });

                  // Adicionar um marcador clicável no mapa
                  var marker = new google.maps.Marker({
                      position: userLatLng, // Posição inicial do marcador
                      map: map,
                      draggable: true // Permitir que o marcador seja arrastado
                  });

                  // Atualizar os campos de latitude e longitude quando o marcador for movido
                  google.maps.event.addListener(marker, 'dragend', function(event) {
                      var geocoder = new google.maps.Geocoder();
                      document.getElementById('latitude').value = event.latLng.lat();
                      document.getElementById('longitude').value = event.latLng.lng();

                      // Obter o endereço a partir da posição do marcador
                      geocoder.geocode({ 'location': event.latLng }, function(results, status) {
                          if (status === 'OK') {
                              if (results[0]) {
                                  document.getElementById('address').value = results[0].formatted_address;
                                  
                                  // Encontrar componentes de endereço como cidade e país
                                  var addressComponents = results[0].address_components;
                                  for (var i = 0; i < addressComponents.length; i++) {
                                      var types = addressComponents[i].types;
                                      if (types.includes('locality')) { // Cidade
                                          document.getElementById('city').value = addressComponents[i].long_name;
                                      } else if (types.includes('country')) { // País
                                          document.getElementById('country').value = addressComponents[i].long_name;
                                      }
                                  }
                              } else {
                                  console.log('No results found');
                              }
                          } else {
                              console.log('Geocoder failed due to: ' + status);
                          }
                      });
                  });
              });
          } else {
              alert('Geolocation is not supported by this browser.');
          }
      }
    </script>

<!-- Incluir a API do Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA9Ucyv950etBnjniHKNIQkv2kRej3biMY&callback=initMap" async defer></script>


</body>
</html>
