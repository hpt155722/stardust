<?php
// Include database connection file
include "connection.php";

// Start session
session_start();

// Query and fetch data
$sql = "
            SELECT
            COALESCE(l.userID, c.userID) AS notificationUserID,
            p.postID AS postID,
            COALESCE(l.dateLiked, c.dateCommented) AS liked_or_commented_date,
            c.commentText,
            COALESCE(l.likeID, c.commentID) AS likeOrCommentID,
            CASE
                WHEN l.likeID IS NOT NULL THEN 'like'
                WHEN c.commentID IS NOT NULL THEN 'comment'
                ELSE NULL
            END AS type,
            u.username AS username,
            u.profilePic AS profilePic,
            p.imageFilePath AS imageFilePath
        FROM
            posts p
        LEFT JOIN
            likes l ON p.postID = l.postID
        LEFT JOIN
            comments c ON p.postID = c.postID
        JOIN
            users u ON u.userID = COALESCE(l.userID, c.userID)
        WHERE
            p.userID = ?
        ORDER BY
            liked_or_commented_date DESC;

";

$stmt = $conn->prepare($sql);

$loggedInUserID = $_SESSION["loggedInUser"];

$stmt->bind_param('i', $loggedInUserID);
$stmt->execute();

// Process the result set
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Output HTML based on the result
    while ($row = $result->fetch_assoc()) {
        if ($row['notificationUserID'] != $loggedInUserID) {
            $notificationText = '';
            $dateNotified = $row['liked_or_commented_date']; // Assuming actionDate is the date of like/comment

            if ($row['type'] == 'like') {
                // If it's from likes table
                $notificationText = "{$row['username']} liked your post!";
            } else {
                // If it's from comments table
                $notificationText = "{$row['username']} commented <br>on your post:<br><span class='comment'>\"{$row['commentText']}\"</span>";
            }

            //Edit date to be relative
            $timestamp = strtotime($dateNotified);
            $current_timestamp = time();
            $time_diff = $current_timestamp - $timestamp;

            if ($time_diff < 60) {
                $relative_time = 'less than 1 min ago';
            } elseif ($time_diff < 3600) {
                $relative_time = floor($time_diff / 60) . 'm ago';
            } elseif ($time_diff < 86400) {
                $relative_time = floor($time_diff / 3600) . 'h ago';
            } else {
                $relative_time = floor($time_diff / 86400) . 'd ago';
            }

            // Output the HTML
            echo "<div class='notificationContainer'>
                    <div class='accountAndNotification'>
                        <img class='accountProfilePic' src='../../resources/profilePics/{$row['profilePic']}'>
                        <p class='notificationText'>$notificationText</p>
                    </div>
                    <div class='dateAndPostPreview'>
                        <p class='dateNotified'>$relative_time</p>
                        <img class='postPreviewPic' src='../../resources/posts/{$row['imageFilePath']}'>
                    </div>
                </div>";
        }
    }
} else {
    echo "No notifications found.";
}

$stmt->close();
$conn->close();

?>
