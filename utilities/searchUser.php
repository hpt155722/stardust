<?php

// Include database connection
include "connection.php";

// Check if search query is set
if(isset($_GET['searchquery'])) {
    $searchquery = $_GET['searchquery'];

    // Prepare a SQL statement
    $sql = "SELECT * FROM users WHERE username LIKE ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // Handle preparation error
        die('MySQL prepare error: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $param = "%{$searchquery}%";
    $stmt->bind_param("s", $param);

    // Execute query
    if (!$stmt->execute()) {
        // Handle execution error
        die('MySQL execute error: ' . htmlspecialchars($stmt->error));
    }

    // Get result set
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $username = htmlspecialchars($row['username']);
            $profilePic = htmlspecialchars($row['profilePic']);
            $userID = htmlspecialchars($row['userID']); 

            // Output HTML for each result
            echo "<div class='resultContainer'>
                    <img class='profilePicture' src='../../resources/profilePics/$profilePic'>
                    <p class='username' onclick = 'loadProfileView($userID)'>$username</p>
                </div>";
        }
    } else {
        echo "No users found";
    }

    // Close statement
    $stmt->close();
} else {
    echo "No search query provided";
}

// Close database connection
$conn->close();

?>
