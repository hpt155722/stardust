<?php
    // Include database connection
    include "connection.php";

    // Query to select the first 5 users ordered by dateJoined (newest first)
    $query = "SELECT * FROM users ORDER BY dateJoined DESC LIMIT 5";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Check if there are any rows returned
    if (mysqli_num_rows($result) > 0) {
        // Loop through each row
        while ($row = mysqli_fetch_assoc($result)) {
            $profilePic = $row['profilePic'];
            $username = $row['username']; 
            $userID = $row['userID'];

            // Echo out the HTML structure for each row
            echo "<div class='resultContainer'>
                    <img class='profilePicture' src='../../resources/profilePics/$profilePic'>
                    <p class='username' onclick = 'loadProfileView($userID, true)' >$username</p>
                </div>";
        }
    } else {
        echo "No users found.";
    }

    // Free result set
    mysqli_free_result($result);

    // Close connection
    mysqli_close($conn);
?>
