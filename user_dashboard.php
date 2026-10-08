<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #ecf0f1;
      margin: 0;
    }
    header {
      background: #3498db;
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
      color: #2980b9;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <header>
    <h1>User Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['user_name']; ?>!</p>
  </header>
  <div class="content">
    <h2>Book Your Flight</h2>
    <p>You can search and book flights here.</p>
    <!-- Future links -->
    <a href="search_flights.php">🔍 Search Flights</a><br>
    <a href="my_bookings.php">📄 My Bookings</a><br><br>
    <a href="logout.php">🔒 Logout</a>
  </div>
</body>
</html>
