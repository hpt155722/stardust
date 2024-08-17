<?php
    $servername = "localhost";
    $database = "u452270634_stardust";
    $username = "u452270634_starduster";
    $password = "4K>ASXOT5k";
     
    // Create connection
     
    $conn = mysqli_connect($servername, $username, $password, $database);
     

    if ($conn -> connect_error) {
        echo "<p>Sorry, there was a problem connecting. Please try again later.</p>";
        exit();
    }
?>