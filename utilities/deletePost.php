<?php

// Hide PHP errors from being displayed
// error_reporting(0);

// Include database connection file
include("connection.php");

if (isset($_GET['postID'])) {
    $postID = $_GET['postID'];

    $sql = "DELETE FROM posts WHERE postID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param('i', $postID); // 'i' indicates the type of parameter (integer)

        // Execute statement
        if ($stmt->execute()) {
            // Delete successful
            echo "Post deleted successfully";
        } else {
            // Delete failed
            echo "Error deleting post";
        }

        // Close statement
        $stmt->close();
    } else {
        // Prepare statement failed
        echo "Prepare statement failed";
    }
} else {
    // If postID is not received
    echo "postID parameter not provided";
}
?>
