<?php
session_start();

// Restrict access to admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// DB Connection
$conn = new mysqli("localhost", "root", "", "airline");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get flight ID
if (!isset($_GET['id'])) {
    header("Location: manage_flights.php");
    exit;
}
$id = $_GET['id'];

// Fetch existing flight data
$stmt = $conn->prepare("SELECT * FROM flights WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$flight = $result->fetch_assoc();

if (!$flight) {
    echo "Flight not found.";
    exit;
}

// Update logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $flight_number = $_POST['flight_number'];
    $airline       = $_POST['airline'];
    $departure     = $_POST['departure'];
    $arrival       = $_POST['arrival'];
    $date          = $_POST['date'];
    $time          = $_POST['time'];
    $seats         = $_POST['seats'];
    $fare          = $_POST['fare'];

    $update = $conn->prepare("UPDATE flights SET flight_number=?, airline=?, departure=?, arrival=?, date=?, time=?, seats=?, fare=? WHERE id=?");
    $update->bind_param("ssssssdii", $flight_number, $airline, $departure, $arrival, $date, $time, $seats, $fare, $id);

    if ($update->execute()) {
        header("Location: manage_flights.php");
        exit;
    } else {
        echo "Failed to update flight.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Flight</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f7f8;
            padding: 30px;
        }
        form {
            background: white;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Edit Flight</h2>

<form method="POST">
    <input type="text" name="flight_number" required placeholder="Flight Number" value="<?= $flight['flight_number'] ?>">
    <input type="text" name="airline" required placeholder="Airline" value="<?= $flight['airline'] ?>">
    <input type="text" name="departure" required placeholder="From" value="<?= $flight['departure'] ?>">
    <input type="text" name="arrival" required placeholder="To" value="<?= $flight['arrival'] ?>">
    <input type="date" name="date" required value="<?= $flight['date'] ?>">
    <input type="time" name="time" required value="<?= $flight['time'] ?>">
    <input type="number" name="seats" required placeholder="Total Seats" value="<?= $flight['seats'] ?>">
    <input type="number" name="fare" required placeholder="Fare in ₹" step="0.01" value="<?= $flight['fare'] ?>">

    <button type="submit">Update Flight</button>
</form>

</body>
</html>
