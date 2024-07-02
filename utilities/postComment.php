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
        $currentDateTime = date('Y-m-d H:i:s'); 
        $query = "INSERT INTO comments (userID, commentText, postID, dateCommented) VALUES ('$userID', '$commentText', '$postID', '$currentDateTime')";
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
                        <div style='display: flex; align-items:center'>
                            <img class='commentorProfilePic' src='../../resources/profilePics/" . $commentRow['profilePic'] . "'>
                            <div class='commentText'>
                                <p class='commentorUsername'>" . $commentRow['username'] . "</p>
                                <p class='commentorComment'>" . $commentRow['commentText'] . "</p>
                            </div>
                        </div>
                        <div style='display: flex; align-items:center'>
                            <p class='commentDate'>" . $formattedCommentDate . "</p>";
            
                    // Check if the comment belongs to the logged-in user
                    if ($commentRow['userID'] == $_SESSION['loggedInUser'] || $commentRow['userID'] ==  $_SESSION['loggedInUser']) {
                        $commentsData .= "<img class='commentMenu' src='../../resources/images/ellipsis.png' onclick='openDeleteComment(\"" . $commentRow['commentID'] . "\")'>";
                    }
            
                    $commentsData .= "</div>
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
