<?php
// search_flights.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'airline';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$flights = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $date = $_POST['date'];

    // Corrected column names: from_city, to_city
    $stmt = $conn->prepare("SELECT * FROM flights WHERE departure = ? AND arrival = ? AND date = ?");
    $stmt->bind_param("sss", $origin, $destination, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $flights = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Flights</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #74ebd5, #acb6e5);
      padding: 20px;
    }
    .container {
      max-width: 700px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
    }
    form input, form select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
    }
    button:hover {
      background-color: #2980b9;
    }
    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #3498db;
      color: white;
    }
    a.book-btn {
      text-decoration: none;
      background: #2ecc71;
      color: white;
      padding: 6px 10px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Search Flights</h2>
    <form method="POST" action="">
      <input type="text" name="origin" required placeholder="Origin (e.g., Mumbai)">
      <input type="text" name="destination" required placeholder="Destination (e.g., Delhi)">
      <input type="date" name="date" required>
      <button type="submit">Search</button>
    </form>

    <?php if (!empty($flights)): ?>
    <h3>Available Flights:</h3>
    <table>
      <tr>
        <th>Flight</th>
        <th>Date</th>
        <th>Time</th>
        <th>Seats</th>
        <th>Fare</th>
        <th>Action</th>
      </tr>
      <?php foreach ($flights as $flight): ?>
      <tr>
        <td><?= htmlspecialchars($flight['airline']) ?></td>
        <td><?= $flight['date'] ?></td>
        <td><?= $flight['time'] ?></td>
        <td><?= $flight['seats'] ?></td>
        <td>₹<?= $flight['fare'] ?></td>
        <td><a href="book_flights.php?flight_id=<?= $flight['id'] ?>" class="book-btn">Book</a></td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
      <p>No flights found for the selected criteria.</p>
    <?php endif; ?>
  </div>
</body>
</html>
