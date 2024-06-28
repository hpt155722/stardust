<?php
    // Include database connection file
    include "connection.php";

    // Start session
    session_start();

    // Query to fetch posts from users that the logged-in user is following, as well as their own posts, sorted by datePosted descending
    $loggedInUserID = $_SESSION['loggedInUser'];
    $query = "SELECT p.*, u.username, u.profilePic 
              FROM posts p 
              INNER JOIN users u ON p.userID = u.userID 
              LEFT JOIN relationships r ON p.userID = r.followingID AND r.followerID = $loggedInUserID
              WHERE p.userID = $loggedInUserID OR r.followerID = $loggedInUserID 
              ORDER BY p.datePosted DESC";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $postID = $row["postID"];
            $userID = $row["userID"];
            $username = $row["username"];
            $profilePic = $row["profilePic"];
            $imageFilePath = $row["imageFilePath"];
            $caption = $row["caption"];
            $datePosted = date("g:iA • m.d.y", strtotime($row["datePosted"]));

            // Query to count comments for this post
            $commentQuery = "SELECT COUNT(*) AS commentCount FROM comments WHERE postID = $postID";
            $commentResult = mysqli_query($conn, $commentQuery);
            $commentCount = mysqli_fetch_assoc($commentResult)["commentCount"];

            // Query to count likes for this post
            $likeQuery = "SELECT COUNT(*) AS likeCount FROM likes WHERE postID = $postID";
            $likeResult = mysqli_query($conn, $likeQuery);
            $likeCount = mysqli_fetch_assoc($likeResult)["likeCount"];

            // Determine if logged in user is the owner of the post
            $showMenu = $row["userID"] == $_SESSION["loggedInUser"];

            // Check if the current user has liked this post
            $likeCheckQuery = "SELECT COUNT(*) AS liked FROM likes WHERE postID = $postID AND userID = $loggedInUserID";
            $likeCheckResult = mysqli_query($conn, $likeCheckQuery);
            $liked = mysqli_fetch_assoc($likeCheckResult)["liked"];

            // Output HTML for each post
            echo "<div class='postContainer' id='post$postID'>";
            echo "<div class='posterInfo'>";
            echo "<img class='posterProfilePic' src='../../resources/profilePics/$profilePic'>";
            echo "<p class='postUsername' onclick='loadProfileView($userID, false)'>$username</p>";
            if ($showMenu) {
                echo "<img class='postMenu' src='../../resources/images/ellipsis.png' onclick='openEditPost($postID, \"feedPage\")'>";
            }
            echo "</div>";

            if (file_exists("../resources/posts/" . $imageFilePath)) {
                echo "<img class='postImage' src='../../resources/posts/$imageFilePath'>";
            } else {
                // Display default image
                echo "<img class='postImage' src='../../resources/images/imageNotFound.png'>";
            }
            echo "<div class='postFooter'>";
            echo "<div class='postText'>";
            if (!empty($caption)) {
                echo "<p class='postCaption'>$caption</p>";
            }
            echo "<p class='postDate'>$datePosted</p>";
            echo "</div>";
            echo "<div class='postIcons'>";
            echo "<p class='postCommentCount'>$commentCount</p>";
            echo "<img class='postCommentIcon' onclick='loadPostView($postID, \"feedPage\")' src='../../resources/images/comment.png' data-postid='$postID'>";
            echo "<p class='postLikeCount'>$likeCount</p>";
            if ($liked > 0) {
                echo "<img class='postHeartIcon' data-postID='$postID' src='../../resources/images/likedHeart.png' onclick='toggleLike(this);'>";
            } else {
                echo "<img class='postHeartIcon' data-postID='$postID' src='../../resources/images/unlikedHeart.png' onclick='toggleLike(this);'>";
            }
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "no posts yet";
    }

    // Close connection
    mysqli_close($conn);
?>
