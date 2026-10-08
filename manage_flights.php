<?php
session_start();

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// DB Connection
$conn = new mysqli("localhost", "root", "", "airline");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Delete flight if requested
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM flights WHERE id = $id");
    header("Location: manage_flights.php");
    exit;
}

// Get all flights
$result = $conn->query("SELECT * FROM flights ORDER BY date, time");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Flights</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      padding: 40px;
    }
    h2 {
      text-align: center;
      color: #333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      margin-top: 20px;
    }
    th, td {
      padding: 12px 10px;
      border-bottom: 1px solid #ccc;
      text-align: center;
    }
    th {
      background: #3498db;
      color: white;
    }
    a {
      padding: 6px 10px;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 14px;
    }
    .edit-btn {
      background: #27ae60;
    }
    .delete-btn {
      background: #e74c3c;
    }
    .top-links {
      text-align: right;
      margin-bottom: 15px;
    }
    .top-links a {
      background: #2980b9;
      margin-left: 10px;
    }
  </style>
</head>
<body>
  <h2>Manage Flights</h2>

  <div class="top-links">
    <a href="add_flight.php">Add New Flight</a>
    <a href="admin_dashboard.php">Back to Dashboard</a>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Flight No.</th>
      <th>Airline</th>
      <th>From</th>
      <th>To</th>
      <th>Date</th>
      <th>Time</th>
      <th>Seats</th>
      <th>Fare (₹)</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['flight_number'] ?></td>
      <td><?= $row['airline'] ?></td>
      <td><?= $row['departure'] ?></td>
      <td><?= $row['arrival'] ?></td>
      <td><?= $row['date'] ?></td>
      <td><?= $row['time'] ?></td>
      <td><?= $row['seats'] ?></td>
      <td><?= $row['fare'] ?></td>
      <td>
        <a href="edit_flight.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
        <a href="manage_flights.php?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Delete this flight?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
