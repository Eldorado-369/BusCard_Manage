<!-- <?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "buscardmanagementsystem";

date_default_timezone_set("Asia/Kolkata");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $latitude = filter_var($_POST['latitude'], FILTER_VALIDATE_FLOAT);
    $longitude = filter_var($_POST['longitude'], FILTER_VALIDATE_FLOAT);
    $timestamp = date("Y-m-d H:i:s");

    if ($latitude !== false && $longitude !== false) {

        $stmt = $conn->prepare("INSERT INTO location (latitude, longitude, timestamp) VALUES (?, ?, ?)");
        $stmt->bind_param("dds", $latitude, $longitude, $timestamp);

        if ($stmt->execute()) {
            echo "Location updated successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid latitude or longitude values";
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>  -->

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "buscardmanagementsystem";

// Set timezone to IST
date_default_timezone_set("Asia/Kolkata");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Check if the request is valid
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Assuming `busNumber` is passed as a query parameter to identify the bus
    $busNumber = isset($_GET['busNumber']) ? filter_var($_GET['busNumber'], FILTER_SANITIZE_STRING) : null;

    if (!empty($busNumber)) {
        // Fetch the latest location for the bus number
        $stmt = $conn->prepare("SELECT latitude, longitude, timestamp FROM location WHERE busNumber = ? ORDER BY timestamp DESC LIMIT 1");
        $stmt->bind_param("s", $busNumber);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $location = $result->fetch_assoc();
                echo json_encode($location);
            } else {
                echo json_encode(["error" => "No location data found for the bus."]);
            }
        } else {
            echo json_encode(["error" => "Error fetching location data: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Bus number is required."]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}

// Close connection
$conn->close();
?>
