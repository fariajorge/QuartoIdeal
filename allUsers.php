<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  // Redirect the user to the login page or another appropriate location
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <!-- style -->
  <link href="css/styleBooking.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

  <title>All Users</title>
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
          <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="DB/db_logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <h1>All Users</h1>

  <div class="container mt-5">
    <form class="form-inline mb-3" method="GET" action="allUsers.php">
      <div class="form-group">
        <label for="searchEmail" class="mr-2">Search by Email:</label>
        <input type="text" name="email" id="searchEmail" class="form-control mr-2" placeholder="Enter email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>">
      </div>
      <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>User ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
        // Include the database connection file
        require_once "DB/db_connection.php";

        // Retrieve users from the database based on the search criteria
        $emailFilter = '';
        if (isset($_GET['email']) && !empty($_GET['email'])) {
          $email = $conn->real_escape_string($_GET['email']);
          $emailFilter = "WHERE email LIKE '%$email%'";
        }

        $sql = "SELECT id, username, email, role FROM users $emailFilter";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $userId = $row['id'];
            $username = $row['username'];
            $email = $row['email'];
            $role = $row['role'];

            echo "<tr>";
            echo "<form action='DB/update_role.php' method='POST'>";
            echo "<td>$userId</td>";
            echo "<td>$username</td>";
            echo "<td>$email</td>";
            echo "<td>
                    <input type='hidden' name='user_id' value='$userId'>
                    <select name='role' class='form-control'>
                      <option value='client'" . ($role == 'client' ? ' selected' : '') . ">Client</option>
                      <option value='worker'" . ($role == 'worker' ? ' selected' : '') . ">Worker</option>
                      <option value='admin'" . ($role == 'admin' ? ' selected' : '') . ">Admin</option>
                    </select>
                  </td>";
            echo "<td><button type='submit' class='btn btn-primary'>Update</button></td>";
            echo "</form>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='5'>No users found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
