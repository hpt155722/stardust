<?php
    session_start();

    // Include database connection file
    include("connection.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $postID = $_POST['postID'];
        $userID = $_SESSION['loggedInUser'];

        // Check if the user has already liked the post to prevent unauthorized removal (optional)
        $checkQuery = "SELECT COUNT(*) as count FROM likes WHERE postID = ? AND userID = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('ii', $postID, $userID);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            // User has liked the post, proceed to remove
            $removeQuery = "DELETE FROM likes WHERE postID = ? AND userID = ?";
            $removeStmt = $conn->prepare($removeQuery);
            $removeStmt->bind_param('ii', $postID, $userID);
            $removeResult = $removeStmt->execute();

            // Check if the removal was successful
            if ($removeResult) {
                echo "Like removed successfully!";
            } else {
                echo "Error removing the like.";
            }
        } else {
            echo "You have not liked this post yet.";
        }
    }
?>
