<?php
// get_latest_location.php

header('Content-Type: application/json');

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "buscardmanagementsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Query to get the latest location based on the most recent timestamp
$sql = "SELECT latitude, longitude, timestamp FROM location ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Convert timestamp to Indian Standard Time (IST)
    $timestamp = new DateTime($row['timestamp'], new DateTimeZone('UTC'));
    $timestamp->setTimezone(new DateTimeZone('Asia/Kolkata'));
    $row['timestamp'] = $timestamp->format('Y-m-d H:i:s'); // Format as "YYYY-MM-DD HH:MM:SS" in IST

    // Return the result as JSON
    echo json_encode($row);
} else {
    echo json_encode(["error" => "No location data available"]);
}

// Close the connection
$conn->close();
?>
