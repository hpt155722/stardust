<?php
// Include database connection file
include "connection.php";

// Start session
session_start();

// Query to fetch posts sorted by datePosted descending
$query = "SELECT p.*, u.username, u.profilePic 
          FROM posts p 
          INNER JOIN users u ON p.userID = u.userID 
          ORDER BY p.datePosted ASC";
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
        $loggedInUserID = $_SESSION["loggedInUser"];
        $likeCheckQuery = "SELECT COUNT(*) AS liked FROM likes WHERE postID = $postID AND userID = $loggedInUserID";
        $likeCheckResult = mysqli_query($conn, $likeCheckQuery);
        $liked = mysqli_fetch_assoc($likeCheckResult)["liked"];

        // Output HTML for each post
        echo "<div class='postContainer'>";
        echo "<div class='posterInfo'>";
        echo "<img class='posterProfilePic' src='../../resources/profilePics/$profilePic'>";
        echo "<p class='postUsername' data-userID='$userID'>$username</p>";
        if ($showMenu) {
            echo "<img class='postMenu' data-postID='$postID' src='../../resources/images/ellipsis.png' onclick='openEditPost($postID)'>";
        }
        echo "</div>";
        echo "<img class='postImage' src='../../resources/posts/$imageFilePath'>";
        echo "<div class='postFooter'>";
        echo "<div class='postText'>";
        echo "<p class='postCaption'>$caption</p>";
        echo "<p class='postDate'>$datePosted</p>";
        echo "</div>";
        echo "<div class='postIcons'>";
        echo "<p class='postCommentCount'>$commentCount</p>";
        echo "<img class='postCommentIcon' data-postID='$postID' src='../../resources/images/comment.png' data-postid='$postID'>";
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
    echo "No posts found.";
}

// Close connection
mysqli_close($conn);
?>
