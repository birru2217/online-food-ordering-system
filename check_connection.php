<?php
include 'includes/config.php';

// Show current database name
$result = mysqli_query($conn, "SELECT DATABASE()");
$row = mysqli_fetch_array($result);
echo "Connected to database: " . $row[0] . "<br><br>";

// Show all tables in current database
$result2 = mysqli_query($conn, "SHOW TABLES");
echo "Tables in this database:<br>";
if(mysqli_num_rows($result2) > 0) {
    while($row2 = mysqli_fetch_array($result2)){
        echo "- " . $row2[0] . "<br>";
    }
} else {
    echo "<span style='color:red'>NO TABLES FOUND</span>";
}

// Check users table specifically
$result3 = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
if(mysqli_num_rows($result3) > 0) {
    echo "<br>✅ users table EXISTS";
} else {
    echo "<br>❌ users table MISSING";
}
?>
