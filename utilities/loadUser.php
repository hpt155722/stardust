<?php
    // Include database connection file
    include("connection.php");

    // Check if userID is provided via GET request
    if (isset($_GET['userID'])) {
        $userID = $_GET['userID'];
        
        // Check if the logged-in user's session is set
        session_start();
        if (isset($_SESSION['loggedInUser'])) {
            $loggedInUserID = $_SESSION['loggedInUser'];

            // Prepare SQL statement to check if the logged-in user follows the displayed user
            $stmt_follow = $conn->prepare("
                SELECT COUNT(*) AS ifFollowing
                FROM relationships
                WHERE followerID = ? AND followingID = ?
            ");
            $stmt_follow->bind_param('ii', $loggedInUserID, $userID);
            $stmt_follow->execute();
            $stmt_follow->bind_result($ifFollowing);
            $stmt_follow->fetch();
            $stmt_follow->close();
        } else {
            // If session not set, assume user does not follow
            $ifFollowing = 0;
        }

        // Prepare SQL statement to fetch user information including followers count and post count
                $stmt = $conn->prepare("
                SELECT 
                    u.username, 
                    u.biography,
                    u.profilePic,
                    COUNT(p.postID) AS post_count,
                    COUNT(r.followerID) AS followers_count
                FROM 
                    users u 
                LEFT JOIN 
                    relationships r ON u.userID = r.followingID 
                LEFT JOIN
                    posts p ON u.userID = p.userID
                WHERE 
                    u.userID = ?
                GROUP BY 
                    u.userID
            ");
    
        $stmt->bind_param('i', $userID); // 'i' indicates the userID is an integer
        $stmt->execute();

        // Bind result variables
        $stmt->bind_result($username, $bio, $profilePic, $post_count, $followers_count);

        // Fetch user information
        if ($stmt->fetch()) {
            // Output HTML structure with user information
            echo "
            <img class='userProfilePic' src='../../resources/profilePics/$profilePic'>
            <div class='userTextInfo'>
                <div class='usernameAndFollowersCount'>
                    <p class='userUsername'>
                        $username
                    </p>
                    <p class='userFollowersInfo'>
                        $followers_count followers
                    </p>
                </div>
                <p class='userBio'>
                    $bio
                </p>
            </div>";

        
        // Determine follow button image based on $ifFollowing
        if ($ifFollowing == 0) {
            echo "<img class='followButton' onclick='toggleFollow($userID)' src='../../resources/images/follow.png'>";
        } else {
            echo "<img class='followButton following' onclick='toggleFollow($userID)' src='../../resources/images/following.png'>";
        }
        
            // Check if user has any posts
            if ($post_count == 0) {
                echo "User has no posts";
            }
        } else {
            // Handle case where user with given userID is not found
            echo "User not found";
        }

        // Close statement
        $stmt->close();
    } else {
        // Handle case where userID is not provided
        echo "userID parameter is missing";
    }

    // Close connection
    $conn->close();
?>
