<?php

include 'includes/config.php';

$result = mysqli_query($conn, "SHOW DATABASES");

while($row = mysqli_fetch_array($result)){

    echo $row[0] . "<br>";
}
?>