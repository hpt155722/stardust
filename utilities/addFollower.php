<?php
    session_start();

    // Include database connection file
    include "connection.php";

    // Check if user is logged in
    if (!isset($_SESSION['loggedInUser'])) {
        // Redirect or handle unauthorized access
        header("Location: login.php");
        exit();
    }

    // Check if userID is provided via POST
    if (isset($_POST['userID'])) {
        $followerID = $_SESSION['loggedInUser'];
        $followingID = $_POST['userID'];

        // Prepare and execute SQL query to insert into relationships table
        $stmt = $conn->prepare("INSERT INTO relationships (followerID, followingID) VALUES (?, ?)");
        $stmt->bind_param("ii", $followerID, $followingID);

        if ($stmt->execute()) {
            // Insert successful
            echo "Follower added successfully!";
        } else {
            // Insert failed
            echo "Error adding follower: " . $stmt->error;
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Handle case where userID is not provided
        echo "Error: userID not provided.";
    }
?>
