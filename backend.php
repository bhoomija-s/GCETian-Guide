<?php
// 1. Connect to the Database
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "gcet_guide";

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set headers for JSON (so your JS can understand the response)
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// 2. Handle GET Request (Fetching reviews)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM reviews ORDER BY id DESC";
    $result = $conn->query($sql);

    $reviews = [];
    while($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    echo json_encode($reviews);
}

// 3. Handle POST Request (Saving a review)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // PHP doesn't read JSON automatically, so we do it manually
    $input = json_decode(file_get_contents('php://input'), true);

    $name = $conn->real_escape_string($input['name']);
    $text = $conn->real_escape_string($input['text']);
    $rating = intval($input['rating']);
    $date = date("Y-m-d");

    $sql = "INSERT INTO reviews (name, review_text, rating, review_date) VALUES ('$name', '$text', '$rating', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Review saved successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }
}

$conn->close();
?>