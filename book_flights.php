<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB CONNECTION
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'airline';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $flight_id = $_POST['flight_id'];

    // Check seat availability
    $check = $conn->query("SELECT seats FROM flights WHERE id = $flight_id");
    $row = $check->fetch_assoc();

    if ($row['seats'] > 0) {
        // Book the seat
        $conn->query("INSERT INTO bookings (passenger_name, flight_id) VALUES ('$name', $flight_id)");
        $conn->query("UPDATE flights SET seats = seats - 1 WHERE id = $flight_id");
        echo "Booking successful!";
    } else {
        echo "No seats available.";
    }
}
?>

<h2>Book a Flight</h2>
<form method="post">
    Name: <input type="text" name="name" required><br><br>
    Select Flight:
    <select name="flight_id">
        <?php
        $result = $conn->query("SELECT * FROM flights WHERE seats> 0");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['flight_number']} - {$row['departure']} to {$row['arrival']} ({$row['time']})</option>";
        }
        ?>
    </select><br><br>
    <input type="submit" value="Book Flight">
    <th id="d"><a href="http://localhost/airline/login.php">logout </th>
</form>
