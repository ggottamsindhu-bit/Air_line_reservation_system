<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f8;
      margin: 0;
      padding: 0;
    }
    header {
      background: #2c3e50;
      color: white;
      padding: 15px 30px;
      text-align: center;
    }
    .content {
      padding: 30px;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      color: #3498db;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <header>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['user_name']; ?>!</p>
  </header>
  <div class="content">
    <h2>Flight Management</h2>
    <p>Here you will be able to add, edit, and delete flights.</p>
    <!-- Future links -->
    <a href="add_flight.php">➕ Add Flight</a><br>
    <a href="manage_flights.php">✈️ Manage Flights</a><br><br>
    <a href="logout.php">🔒 Logout</a>
  </div>
</body>
</html>
