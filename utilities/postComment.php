<?php
    // Include database connection file
    include("connection.php");

    session_start();

    // Check if the request is a POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Assuming $_SESSION['loggedInUser'] holds the userID

        // Sanitize input to prevent SQL injection
        $userID = $_SESSION['loggedInUser'];
        $commentText = mysqli_real_escape_string($conn, $_POST['commentText']);
        $postID = $_POST['postID'];

        // Insert comment into the comments table
        $query = "INSERT INTO comments (userID, commentText, postID) VALUES ('$userID', '$commentText', '$postID')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Fetch comments for the post
            $commentsQuery = "SELECT comments.*, users.username, users.profilePic
                              FROM comments
                              LEFT JOIN users ON comments.userID = users.userID
                              WHERE comments.postID = $postID
                              ORDER BY comments.dateCommented DESC";
            
            $commentsResult = mysqli_query($conn, $commentsQuery);

            $commentsData = "";

            if ($commentsResult && mysqli_num_rows($commentsResult) > 0) {
                while ($commentRow = mysqli_fetch_assoc($commentsResult)) {
                    $formattedCommentDate = date("m.d.y", strtotime($commentRow['dateCommented']));
                    $commentsData .= "<div class='commentContainer'>
                        <img class='commentorProfilePic' src='../../resources/profilePics/" . $commentRow['profilePic'] . "'>
                        <div class='commentText'>
                            <p class='commentorUsername'>" . $commentRow['username'] . "</p>
                            <p class='commentorComment'>" . $commentRow['commentText'] . "</p>
                        </div>
                        <p class='commentDate'>" . $formattedCommentDate . "</p>
                    </div>";
                }
                
            } else {
                $commentsData = "<p>No comments yet.</p>";
            }

            // Return output as JSON
            echo $commentsData;

        } else {
            // Handle query error
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Handle if not a POST request
        echo "Method not allowed";
    }

    // Close database connection
    mysqli_close($conn);
?>
