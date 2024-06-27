<?php
    // Include database connection file
    include "connection.php";

    // Start session
    session_start();

    // Initialize an empty variable to store all img tags
    $output = '';

    // Check if user is logged in
    if (isset($_GET['userID'])) {
        $userID = $_GET['userID'];

        // Query to retrieve posts for the user sorted by descending order
        $query = "SELECT postID, imageFilePath FROM posts WHERE userID = ? ORDER BY datePosted DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userID); 
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are posts
        if ($result->num_rows > 0) {
            // Output each post as an image preview
            while ($row = $result->fetch_assoc()) {
                $postID = $row["postID"];
                $imageFilePath = $row["imageFilePath"];

                // Determine the image source
                $imgSrc = file_exists("../resources/posts/" . $imageFilePath) ? "../../resources/posts/$imageFilePath" : "../../resources/images/imageNotFound.png";

                // Append the img tag to the output variable
                $output .= "<img class='userPostPreview' src='$imgSrc' onclick=\"loadPostView($postID)\">";
            }
        } else {
            // No posts found message
            $output = "No posts found.";
        }

        // Free result set
        $stmt->close();
    } else {
        // Handle case where user is not logged in
        $output = "User not logged in.";
    }

    // Close database connection
    $conn->close();

    // Return the accumulated img tags
    echo $output;
?>
