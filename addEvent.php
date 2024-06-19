<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    
    $sql = "INSERT INTO events (title, description, date, time) VALUES ('$title', '$description', '$date', '$time')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Event added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<form method="post" action="add_event.php">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title"><br>
    <label for="description">Description:</label><br>
    <textarea id="description" name="description"></textarea><br>
    <label for="date">Date:</label><br>
    <input type="date" id="date" name="date"><br>
    <label for="time">Time:</label><br>
    <input type="time" id="time" name="time"><br><br>
    <input type="submit" value="Add Event">
</form>
</body>
</html>
