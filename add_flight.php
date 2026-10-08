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

// Add flight logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $flight_number = $_POST['flight_number'];
    $airline       = $_POST['airline'];
    $departure     = $_POST['departure'];
    $arrival       = $_POST['arrival'];
    $date          = $_POST['date'];
    $time          = $_POST['time'];
    $seats         = $_POST['seats'];
    $fare          = $_POST['fare'];

    $stmt = $conn->prepare("INSERT INTO flights (flight_number, airline, departure, arrival, date, time, seats, fare)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssii", $flight_number, $airline, $departure, $arrival, $date, $time, $seats, $fare);

    if ($stmt->execute()) {
        $success = "Flight added successfully.";
    } else {
        $error = "Failed to add flight.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Flight - Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef2f3;
      padding: 50px;
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      width: 500px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      padding: 12px;
      background: #27ae60;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background: #219150;
    }
    .message { margin-bottom: 15px; color: green; }
    .error { color: red; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Add New Flight</h2>

    <?php if (!empty($success)) echo "<div class='message'>$success</div>"; ?>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST" action="">
      <input type="text" name="flight_number" required placeholder="Flight Number">
      <input type="text" name="airline" required placeholder="Airline Name">
      <input type="text" name="departure" required placeholder="Departure City">
      <input type="text" name="arrival" required placeholder="Arrival City">
      <input type="date" name="date" required>
      <input type="time" name="time" required>
      <input type="number" name="seats" required placeholder="Total Seats">
      <input type="number" name="fare" step="0.01" required placeholder="Fare (INR)">
      <button type="submit">Add Flight</button>
    </form>
  </div>
</body>
</html>
