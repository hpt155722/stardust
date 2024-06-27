<?php
    // Include database connection file
    include("connection.php");

    session_start();
    
    // Check if commentID is set and is a number
    if (isset($_GET['commentID']) && is_numeric($_GET['commentID'])) {
        
        // Sanitize the input (optional, but recommended)
        $commentID = intval($_GET['commentID']);
        $postID = $_GET['postID'];

        // Prepare a SQL statement to delete the comment
        $sql = "DELETE FROM comments WHERE commentID = ?";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            // Handle database error
            echo "Database error";
            exit;
        }
        
        // Bind the parameter
        $stmt->bind_param('i', $commentID);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Deletion successful, fetch updated comments
                        
            $commentsQuery = "SELECT comments.*, users.username, users.profilePic
                              FROM comments
                              LEFT JOIN users ON comments.userID = users.userID
                              WHERE comments.postID = $postID
                              ORDER BY comments.dateCommented DESC";
            
            $commentsResult = mysqli_query($conn, $commentsQuery);
    
            if ($commentsResult && mysqli_num_rows($commentsResult) > 0) {
                $commentsData = "";
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
                    if ($commentRow['userID'] == $_SESSION['loggedInUser']) {
                        $commentsData .= "<img class='commentMenu' src='../../resources/images/ellipsis.png' onclick='openDeleteComment(\"" . $commentRow['commentID'] . "\")'>";
                    }
            
                    $commentsData .= "</div>
                        </div>";
                }
                // Return updated comments data
                echo $commentsData;
            } else {
                // No comments found
                echo "<p>No comments yet.</p>";
            }
        } else {
            // Failed to execute deletion query
            echo "Failed to delete comment";
        }
        
        // Close statement
        $stmt->close();
  
    } else {
        // Invalid commentID or not numeric
        echo "Invalid commentID";
    }

    // Close connection
    $conn->close();
?>
