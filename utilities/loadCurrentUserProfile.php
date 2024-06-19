<?php
// Include database connection file
include("connection.php");

// Start session
session_start();

// Check if user is logged in
if (isset($_SESSION['loggedInUser'])) {
    try {
        // Prepare and execute query to fetch user information
        $stmt = $conn->prepare("SELECT username, biography, profilePic, userID FROM users WHERE userID = ?");
        
        // Bind parameter (userID)
        $stmt->bind_param('i', $_SESSION['loggedInUser']);
        
        // Execute statement
        $stmt->execute();
        
        // Get result
        $result = $stmt->get_result();
        
        // Fetch user information
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            $data = [
                'username' => $user['username'],
                'biography' => $user['biography'] ?: 'no biography yet',
                'profilePic' => $user['profilePic'] ?: '../../resources/images/defaultProfilePic.png',
                'userID' => $user['userID']
            ];
            
            echo json_encode($data);
        } else {
            echo json_encode(null); // No user found
        }
        
    } catch (mysqli_sql_exception $e) {
        // Handle mysqli Exception
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // User not logged in
    echo json_encode(null);
}
?>
