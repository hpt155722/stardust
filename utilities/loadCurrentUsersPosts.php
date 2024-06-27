<?php
    // Include database connection file
    include "connection.php";

    // Start session
    session_start();

    // Check if user is logged in
    if (isset($_SESSION["loggedInUser"])) {
        // Get the logged-in user ID
        $loggedInUserID = $_SESSION["loggedInUser"];

        // Query to retrieve posts for the logged-in user sorted by descending order
        $query =
            "SELECT postID, imageFilePath FROM posts WHERE userID = ? ORDER BY datePosted DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $loggedInUserID);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are posts
        if ($result->num_rows > 0) {
            // Output each post as an image preview
            while ($row = $result->fetch_assoc()) {
                $postID = $row["postID"];
                $imageFilePath = $row["imageFilePath"];

                if (file_exists("../resources/posts/" . $imageFilePath)) {
                    echo "<img class='currentUserPostPreview' src='../../resources/posts/$imageFilePath' onclick=\"loadPostView($postID, 'accountsPage')\">";
                } else {
                    // Display default image
                    echo "<img class='currentUserPostPreview' src='../../resources/images/imageNotFound.png' onclick=\"loadPostView($postID, 'accountsPage')\">";
                }
            }
        } else {
            echo "No posts found.";
        }

        // Free result set
        $stmt->close();
    } else {
        // Handle case where user is not logged in
        echo "User not logged in.";
    }

    // Close database connection
    $conn->close();
?>
