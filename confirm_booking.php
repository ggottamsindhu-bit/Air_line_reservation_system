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

// If not coming from search/selection, redirect
if (!isset($_GET['flight_id'])) {
    echo "<script>alert('No flight selected!'); window.location.href='search_flights.php';</script>";
    exit;
}
$flight_id = $_GET['flight_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirm Booking</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #e0f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .booking-box {
      background: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 400px;
    }
    h2 {
      margin-bottom: 20px;
      color: #00796b;
      text-align: center;
    }
    input, button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    button {
      background: #00796b;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background: #004d40;
    }
  </style>
</head>
<body>
  <div class="booking-box">
    <h2>Confirm Booking</h2>
    <form method="POST" action="confirm_booking.php">
      <input type="hidden" name="flight_id" value="<?= htmlspecialchars($flight_id) ?>">
      <input type="text" name="passenger_name" required placeholder="Passenger Name">
      <input type="number" name="seats" required placeholder="Number of Seats" min="1">
      <button type="submit">Confirm Booking</button>
    </form>
  </div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $flight_id = $_POST['flight_id'];
    $passenger_name = $_POST['passenger_name'];
    $seats = $_POST['seats'];

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, flight_id, passenger_name, seats) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $user_id, $flight_id, $passenger_name, $seats);

    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!'); window.location.href='my_bookings.php';</script>";
    } else {
        echo "<script>alert('Booking failed. Try again.');</script>";
    }
}
?>
