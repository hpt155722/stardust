<?php
    // Hide PHP errors from being displayed
    //error_reporting(0);

    // Include database connection file
    include("connection.php");

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $bioContent = htmlspecialchars($_POST['bioContent']);
        $userID = $_SESSION['loggedInUser'];

        try {
            // Fetch user information
            $sqlFetchUser = "SELECT * FROM users WHERE userID = ?";
            $stmtFetchUser = $conn->prepare($sqlFetchUser);
            $stmtFetchUser->bind_param('i', $userID);
            $stmtFetchUser->execute();
            $user = $stmtFetchUser->get_result()->fetch_assoc();

            // Update user biography
            $sqlUpdateBio = "UPDATE users SET biography = ? WHERE userID = ?";
            $stmtUpdateBio = $conn->prepare($sqlUpdateBio);
            $stmtUpdateBio->bind_param('si', $bioContent, $userID);
            $stmtUpdateBio->execute();

            // Check if update was successful
            $rowsAffected = $stmtUpdateBio->affected_rows;
            if ($rowsAffected > 0) {
                echo "Bio saved successfully.";
            } else {
                echo "Failed to save bio.";
            }
        } catch (Exception $e) {
            echo "Error saving biography: " . $e->getMessage();
        }
    }
?>
