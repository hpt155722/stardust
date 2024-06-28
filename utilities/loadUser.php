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

        // Prepare SQL statement to fetch user information including post count
        $stmt_user = $conn->prepare("
            SELECT 
                u.username, 
                u.biography,
                u.profilePic
            FROM 
                users u 
            WHERE 
                u.userID = ?
        ");

        $stmt_user->bind_param('i', $userID); // 'i' indicates the userID is an integer
        $stmt_user->execute();

        // Bind result variables
        $stmt_user->bind_result($username, $bio, $profilePic);

        // Fetch user information
        if ($stmt_user->fetch()) {
            // Output HTML structure with user information
            echo "
            <img class='userProfilePic' src='../../resources/profilePics/$profilePic'>
            <div class='userTextInfo'>
                <div class='usernameAndFollowersCount'>
                    <p class='userUsername'>
                        $username
                    </p>
                    <p class='userFollowersInfo'>
                        Loading followers count... <!-- Placeholder for followers count -->
                    </p>
                </div>
                <p class='userBio'>
                    $bio
                </p>
            </div>";

            // Close $stmt_user
            $stmt_user->close();

            // Prepare SQL statement to fetch followers count
            $stmt_followers = $conn->prepare("
                SELECT COUNT(*) AS followers_count
                FROM relationships
                WHERE followingID = ?
            ");
            $stmt_followers->bind_param('i', $userID);
            $stmt_followers->execute();
            $stmt_followers->bind_result($followers_count);
            $stmt_followers->fetch();
            $stmt_followers->close();

            // Output followers count
            echo "<script>document.querySelector('.userFollowersInfo').innerHTML = '$followers_count followers';</script>";

            // Output follow button based on $ifFollowing
            if ($userID != $_SESSION['loggedInUser']) {
                if ($ifFollowing == 0) {
                    echo "<img class='followButton' onclick='toggleFollow($userID)' src='../../resources/images/follow.png'>";
                } else {
                    echo "<img class='followButton following' onclick='toggleFollow($userID)' src='../../resources/images/following.png'>";
                }
            }
        } else {
            // Handle case where user with given userID is not found
            echo "User not found";
        }
    } else {
        // Handle case where userID is not provided
        echo "userID parameter is missing";
    }

    // Close connection
    $conn->close();
?>
