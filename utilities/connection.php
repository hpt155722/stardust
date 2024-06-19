<?php
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database_name = "stardustdatabase";
    $port = 3307;

    $conn = new mysqli($server_name, $username, $password, $database_name, $port);

    if ($conn -> connect_error) {
        echo "<p>Sorry, there was a problem connecting to the database. Please try again later.</p>";
        exit();
    }
?>
