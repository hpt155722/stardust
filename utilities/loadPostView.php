<?php
    include("connection.php");
    session_start();

    // Function to sanitize user input
    function sanitize($conn, $input) {
        return mysqli_real_escape_string($conn, $input);
    }

    // Initialize output array
    $output = array();

    // Retrieve postID from GET parameters
    if(isset($_GET['postID'])) {
        $postID = sanitize($conn, $_GET['postID']);

        // Fetch post details query
        $query = "SELECT posts.*, users.username, users.profilePic,
                  COUNT(likes.postID) AS like_count,
                  EXISTS (
                      SELECT 1 FROM likes
                      WHERE likes.postID = posts.postID
                      AND likes.userID = '" . sanitize($conn, $_SESSION['loggedInUser']) . "'
                  ) AS liked_by_user
                  FROM posts 
                  LEFT JOIN users ON posts.userID = users.userID 
                  LEFT JOIN likes ON posts.postID = likes.postID
                  WHERE posts.postID = $postID
                  GROUP BY posts.postID";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Format datePosted field
            $formattedDatePosted = date("g:iA • m.d.y", strtotime($row["datePosted"]));

            // Build post data structure
            $postData = "<div class='postContainer'>
                <div class='posterInfo'>
                    <img class='posterProfilePic' src='../../resources/profilePics/" . $row['profilePic'] . "'>
                    <p class='postUsername'>" . $row['username'] . "</p>
                    <img class='postMenu' src='../../resources/images/ellipsis.png' onclick=\"openEditPost('$postID', 'postView')\">
                </div>
                <img class='postImage' src='";


            // Check if the image file exists
            if (file_exists("../resources/posts/" . $row['imageFilePath'])) {
                $postData .= "../../resources/posts/" . $row['imageFilePath'];
            } else {
                $postData .= "../../resources/images/imageNotFound.png";
            }

            $postData .= "'>
                <div class='postFooter'>
                <div class='postText'>
                    <p class='postCaption'>" . $row['caption'] . "</p>
                    <p class='postDate'>" . $formattedDatePosted . "</p> <!-- Updated date format here -->
                </div>";
                
            $postData .= "</div>
                </div>
                </div>"; // Close remaining divs

            // Add postData to output array
            $output['postData'] = $postData;

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

            // Add commentsData to output array
            $output['commentsData'] = $commentsData;

        } else {
            // If post not found, set output error message
            $output['error'] = "Post not found";
        }
    } else {
        // If postID not set, set output error message
        $output['error'] = "PostID parameter missing";
    }

    // Output the combined JSON-encoded output array
    echo json_encode($output);
?>
