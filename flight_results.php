<?php
$conn = new mysqli('localhost', 'root', '', 'airline');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$from = $_POST['from'];
$to = $_POST['to'];
$date = $_POST['date'];

$query = $conn->prepare("SELECT * FROM flights WHERE departure = ? AND arrival = ? AND date = ?");
$query->bind_param("sss", $from, $to, $date);
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Flight Results</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f7f9;
      margin: 0;
      padding: 20px;
    }
    h2 {
      text-align: center;
      color: #2c3e50;
    }
    .flight-card {
      background-color: white;
      border: 1px solid #ddd;
      padding: 20px;
      margin: 20px auto;
      width: 90%;
      max-width: 600px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .flight-card p {
      margin: 8px 0;
    }
    .book-btn {
      display: inline-block;
      padding: 10px 15px;
      background-color: #27ae60;
      color: white;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
    }
    .book-btn:hover {
      background-color: #219150;
    }
  </style>
</head>
<body>
  <h2>Available Flights</h2>
  <?php
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          echo "<div class='flight-card'>";
          echo "<p><strong>Flight:</strong> " . $row['flight_name'] . "</p>";
          echo "<p><strong>From:</strong> " . $row['departure'] . "</p>";
          echo "<p><strong>To:</strong> " . $row['arrival'] . "</p>";
          echo "<p><strong>Date:</strong> " . $row['date'] . "</p>";
          echo "<p><strong>Time:</strong> " . $row['time'] . "</p>";
          echo "<p><strong>Fare:</strong> ₹" . $row['fare'] . "</p>";
          echo "<a class='book-btn' href='book_flight.php?flight_id=" . $row['id'] . "'>Book Now</a>";
          echo "</div>";
      }
  } else {
      echo "<p style='text-align:center;'>No flights found.</p>";
  }
  ?>
</body>
</html>
