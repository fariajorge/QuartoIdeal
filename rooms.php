<!--
  Este arquivo exibe os detalhes de um hotel específico e suas salas disponíveis, permitindo que 
  os utilizadores reservem salas e que os administradores e trabalhadores gerenciem as salas.
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
if (!isset($_GET['id'])) {
  // Redireciona o utilizador se o ID do hotel estiver faltando
  header("Location: home.php");
  exit();
}

$hotelId = $_GET['id'];

// Recupera os detalhes do hotel do banco de dados
$query = "SELECT * FROM hotels WHERE id = $hotelId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  // Busca o registro do hotel
  $hotel = $result->fetch_assoc();

  // Busca os detalhes do hotel
  $hotelName = $hotel['name'];
  $address = $hotel['address'];
  $city = $hotel['city'];
  $country = $hotel['country'];
  $imageData = $hotel['image'];
}
else {
  // Redireciona o utilizador se o hotel não for encontrado
  header("Location: home.php");
  exit();
}

// Recupera os quartos do hotel do banco de dados
$query = "SELECT id, room_number, room_type, description, price_per_night FROM rooms WHERE hotel_id = $hotelId";
$result = $conn->query($query);
$rooms = [];

if ($result->num_rows > 0) {
  // Busca todos os quartos
  while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
  }
}
?>

<!DOCTYPE html>
<html>
<head>

<!-- style -->
    <link href="css/styleRoom.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

  <title>Hotel Details</title>
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

  <p></p>

  <?php
if ($_SESSION['role'] == 'worker' || $_SESSION['role'] == 'admin') {
  echo "<a href='add_room.php?id=$hotelId'><button>Add Room</button></a>";
}
?>
 <div class="hotel-details">
    <div class="hotel-image">
      <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" alt="Hotel Image">
    </div>
    <div class="hotel-info">
      <h1><?php echo $hotelName; ?></h1>
      <p>Address: <?php echo $address; ?></p>
      <p>City: <?php echo $city; ?></p>
      <p>Country: <?php echo $country; ?></p>
      <p></p>
      <p></p>
      <p id="weather-info"></p>
      <p id="forecast-info"></p>
    </div>
  </div>
  </div>

<h2>Rooms</h2>
  <?php
// Verifica se há quartos para exibir
if (!empty($rooms)) {
  foreach ($rooms as $room) {
    echo '
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Room Number: ' . $room['room_number'] . '</h5>
            <p class="card-text">Room Type: ' . $room['room_type'] . '</p>
            <p class="card-text">Description: ' . $room['description'] . '</p>
            <p class="card-text">Price per Night: ' . $room['price_per_night'] . '</p>';

    // Exibe o botão "Reserve Room" para todos os utilizadores
    echo '<button class="btn btn-success btn-spacing" type="button" data-toggle="modal" data-target="#reservationModal" data-room-id="' . $room['id'] . '" data-room-price="' . $room['price_per_night'] . '">Reserve Room</button>';

    // Exibe os botões "Edit" e "Delete" para utilizadores com função de trabalhador ou administrador
    if ($_SESSION['role'] == 'worker' || $_SESSION['role'] == 'admin') {
      echo '<a href="updateRoom.php?id=' . $room['id'] . '"><button class="btn btn-primary btn-spacing" type="button">Edit</button></a>';
    }
    if ($_SESSION['role'] == 'admin') {
      echo '<a href="DB/delete_room.php?id=' . $room['id'] . '"><button class="btn btn-danger" type="button">Delete</button></a>';
    }

    echo '
          </div>
        </div>';
  }
}
else {
  echo "<p>No rooms found for this hotel.</p>";
}
?>


<!-- Modal de Reserva -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reservationModalLabel">Room Reservation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="reservationForm" action="DB/book_room.php" method="post">
          <input type="hidden" name="room_id" id="room_id">
          <div class="form-group">
            <label for="entryDate">Entry Date</label>
            <input type="date" class="form-control" id="entryDate" name="entryDate" required>
          </div>
          <div class="form-group">
            <label for="checkoutDate">Check-out Date</label>
            <input type="date" class="form-control" id="checkoutDate" name="checkoutDate" required>
          </div>
          <div class="form-group">
            <label for="totalCost">Total Cost</label>
            <input type="text" class="form-control" id="totalCost" readonly>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" form="reservationForm">Reserve</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Manipula o evento de exibição do modal
  $('#reservationModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); // Botão que acionou o modal
    var roomId = button.data('room-id'); // Extrai o ID do quarto do atributo data
    var roomPrice = button.data('room-price'); // Extrai o preço do quarto do atributo data
    var modal = $(this);

    console.log(roomId+ "dafadafd");
    console.log(roomPrice+"fadsf");

    // Define o ID do quarto no formulário de reserva
    modal.find('#room_id').val(roomId);

    // Calcula e exibe o custo total com base nas datas selecionadas
    modal.find('#entryDate, #checkoutDate').on('input', function() {
      var entryDate = new Date(modal.find('#entryDate').val());
      var checkoutDate = new Date(modal.find('#checkoutDate').val());

      if (entryDate && checkoutDate && entryDate <= checkoutDate) {
        var timeDiff = Math.abs(checkoutDate.getTime() - entryDate.getTime());
        var numNights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        var totalCost = numNights * roomPrice;
        modal.find('#totalCost').val(totalCost);
      } else {
        modal.find('#totalCost').val('');
      }
    });
  });

// API metreologia 
  const city = "<?php echo $city; ?>";
  const apiKey = 'dac35ba1b50481d979cfeaac208d0561';
  const countryCode = 'PT'; // ISO 3166 country code for Portugal

  
  async function getCoordinates(city) {
    const url = `http://api.openweathermap.org/geo/1.0/direct?q=${city},${countryCode}&limit=1&appid=${apiKey}`;
    const response = await fetch(url);
    const data = await response.json();
    
    if (data.length === 0) {
      throw new Error('City not found');
    }
    
    const { lat, lon } = data[0];
    return { lat, lon };
  }

  async function getWeather(lat, lon) {
    try {
      const weatherUrl = `http://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=pt`;
      const weatherResponse = await fetch(weatherUrl);
      const weatherData = await weatherResponse.json();

      document.getElementById('weather-info').innerText = `Tempo atual: ${weatherData.weather[0].description}, Temperatura: ${weatherData.main.temp}°C`;
    } catch (error) {
      console.error(error);
      document.getElementById('weather-info').innerText = 'Weather information not available';
    }
  }

  async function getForecast(lat, lon) {
  try {
    const forecastUrl = `http://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=pt`;
    const forecastResponse = await fetch(forecastUrl);
    const forecastData = await forecastResponse.json();

    let forecastHtml = '<h3>Previsão do tempo:</h3><ul>';
    let dates = new Set();
    let filteredForecasts = [];

    // Filter to get one forecast entry per day at 12:00 (noon)
    forecastData.list.forEach(forecast => {
      const date = new Date(forecast.dt * 1000);
      const dateString = date.toLocaleDateString();
      const hour = date.getHours();
      
      // Add to filtered forecasts if it's at noon and not already added
      if (hour === 13 && !dates.has(dateString)) {
        dates.add(dateString);
        filteredForecasts.push(forecast);
      }
    });

    // Fallback to first entry of the day if no forecast at 12:00
    forecastData.list.forEach(forecast => {
      const date = new Date(forecast.dt * 1000);
      const dateString = date.toLocaleDateString();
      
      if (!dates.has(dateString)) {
        dates.add(dateString);
        filteredForecasts.push(forecast);
      }
    });

    // Generate HTML for filtered forecasts
    filteredForecasts.forEach(forecast => {
      const date = new Date(forecast.dt * 1000);
      const dateString = date.toLocaleDateString();
      forecastHtml += `<li>Data: ${dateString}, Temperatura: ${forecast.main.temp}°C, ${forecast.weather[0].description}</li>`;
    });

    forecastHtml += '</ul>';
    
    document.getElementById('forecast-info').innerHTML = forecastHtml;
  } catch (error) {
    console.error(error);
    document.getElementById('forecast-info').innerText = 'Forecast information not available';
  }
}



  async function initWeather() {
    try {
      const coordinates = await getCoordinates(city);
      await getWeather(coordinates.lat, coordinates.lon);
      await getForecast(coordinates.lat, coordinates.lon);
    } catch (error) {
      console.error(error);
    }
  }

  initWeather();

</script>

</body>
</html>
