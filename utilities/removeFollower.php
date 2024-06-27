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

        // Prepare and execute SQL query to delete from relationships table
        $stmt = $conn->prepare("DELETE FROM relationships WHERE followerID = ? AND followingID = ?");
        $stmt->bind_param("ii", $followerID, $followingID);

        if ($stmt->execute()) {
            // Deletion successful
            echo "Follower removed successfully!";
        } else {
            // Deletion failed
            echo "Error removing follower: " . $stmt->error;
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Handle case where userID is not provided
        echo "Error: userID not provided.";
    }
?>
