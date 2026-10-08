<?php
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
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT f.airline, f.source, f.destination, f.date, f.time, b.passenger_name, b.seats
    FROM bookings b
    JOIN flights f ON b.flight_id = f.id
    WHERE b.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Bookings</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f1f8ff;
      padding: 30px;
    }
    h2 {
      text-align: center;
      color: #2e7d32;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 25px;
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }
    th {
      background-color: #2e7d32;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>
  <h2>My Bookings</h2>
  <table>
    <tr>
      <th>Airline</th>
      <th>From</th>
      <th>To</th>
      <th>Date</th>
      <th>Time</th>
      <th>Passenger</th>
      <th>Seats</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['airline']) ?></td>
      <td><?= htmlspecialchars($row['source']) ?></td>
      <td><?= htmlspecialchars($row['destination']) ?></td>
      <td><?= htmlspecialchars($row['date']) ?></td>
      <td><?= htmlspecialchars($row['time']) ?></td>
      <td><?= htmlspecialchars($row['passenger_name']) ?></td>
      <td><?= htmlspecialchars($row['seats']) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
