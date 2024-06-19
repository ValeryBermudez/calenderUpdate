<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<?php
include 'config.php';

function getEvents($month, $year, $conn) {
    $firstDay = "$year-$month-01";
    $lastDay = date("Y-m-t", strtotime($firstDay));
    
    $sql = "SELECT * FROM events WHERE date BETWEEN '$firstDay' AND '$lastDay'";
    $result = $conn->query($sql);
    
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    
    return $events;
}

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$events = getEvents($month, $year, $conn);

function createCalendar($month, $year, $events) {
    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $firstDayOfMonth = date('w', strtotime("$year-$month-01"));
    $daysInMonth = date('t', strtotime("$year-$month-01"));
    
    echo '<table>';
    echo '<tr>';
    foreach ($daysOfWeek as $day) {
        echo "<th>$day</th>";
    }
    echo '</tr><tr>';
    
    for ($i = 0; $i < $firstDayOfMonth; $i++) {
        echo '<td></td>';
    }
    
    for ($day = 1; $day <= $daysInMonth; $day++) {
        if (($day + $firstDayOfMonth - 1) % 7 == 0) {
            echo '</tr><tr>';
        }
        
        echo '<td>';
        echo $day;
        foreach ($events as $event) {
            if ($event['date'] == "$year-$month-$day") {
                echo '<br>' . $event['title'];
            }
        }
        echo '</td>';
    }
    
    echo '</tr>';
    echo '</table>';
}

$previousMonth = $month == 1 ? 12 : $month - 1;
$previousYear = $month == 1 ? $year - 1 : $year;
$nextMonth = $month == 12 ? 1 : $month + 1;
$nextYear = $month == 12 ? $year + 1 : $year;

$monthName = date("F", mktime(0, 0, 0, $month, 10)); // Get the month name
?>

<h1><?php echo $monthName . " " . $year; ?></h1>
<a href="index.php?month=<?= $previousMonth ?>&year=<?= $previousYear ?>">Previous Month</a>
<a href="index.php?month=<?= $nextMonth ?>&year=<?= $nextYear ?>">Next Month</a>

<?php createCalendar($month, $year, $events); ?>

<a href="#" id="addEventButton">Add Event</a>

<div id="eventForm" class="sidebar">
    <form method="post" id="addEventForm">
        <h2>Add Event</h2>
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title"><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br>
        <label for="date">Date:</label><br>
        <input type="date" id="date" name="date"><br>
        <label for="time">Time:</label><br>
        <input type="time" id="time" name="time"><br><br>
        <input type="submit" value="Add Event">
        <button type="button" id="closeButton">Close</button>
    </form>
</div>

<script>
document.getElementById('addEventButton').addEventListener('click', function() {
    document.getElementById('eventForm').style.display = 'block';
});

document.getElementById('closeButton').addEventListener('click', function() {
    document.getElementById('eventForm').style.display = 'none';
});

document.getElementById('addEventForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    fetch('add_event.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        document.getElementById('eventForm').style.display = 'none';
        window.location.reload();
    })
    .catch(error => console.error('Error:', error));
});
</script>

</body>
</html>
