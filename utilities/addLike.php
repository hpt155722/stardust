<?php
    session_start();

    // Include database connection file
    include("connection.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $postID = $_POST['postID'];
        $userID = $_SESSION['loggedInUser'];

        // Check if the user has already liked the post to prevent duplicate likes (optional)
        $checkQuery = "SELECT COUNT(*) as count FROM likes WHERE postID = ? AND userID = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('ii', $postID, $userID);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] == 0) {
            // User hasn't liked the post yet, proceed to insert
            $insertQuery = "INSERT INTO likes (postID, userID) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param('ii', $postID, $userID);
            $insertResult = $insertStmt->execute();

            // Check if the insert was successful
            if ($insertResult) {
                echo "Liked successfully!";
            } else {
                echo "Error liking the post.";
            }
        } else {
            echo "You have already liked this post.";
        }
    }
?>
