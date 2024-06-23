<?php
    session_start(); 
    include("connection.php"); // Include your database connection file

    // Check if user is logged in
    if (!isset($_SESSION['loggedInUser'])) {
        echo("User is not logged in."); // Handle the case where user is not logged in
        exit();
    }

    $userID = $_SESSION['loggedInUser'];

    $currentPassword = $_POST['currentPassword'];
    $createdPassword = $_POST['createdPassword'];

    // Fetch user's data from database based on userID
    $stmt = $conn->prepare("SELECT password FROM users WHERE userID = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify if current password matches hashed password in database
    if (password_verify($currentPassword, $hashedPassword)) {
        // Check if the new password is the same as the current password
        if ($currentPassword === $createdPassword) {
            echo "new password cannot be a used one";
        } else {
            // Passwords match, proceed to update the password
            // Hash the new password before storing it in the database
            $newHashedPassword = password_hash($createdPassword, PASSWORD_BCRYPT);

            // Update the user's password in the database
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE userID = ?");
            $updateStmt->bind_param("ss", $newHashedPassword, $userID);
            if ($updateStmt->execute()) {
                // Password updated successfully
                echo "password updated successfully";
            } else {
                // Handle update failure
                echo "error updating password";
            }
            $updateStmt->close();
        }
    } else {
        // Passwords do not match
        echo "current password is incorrect";
    }

    // Close database connection
    $conn->close();
?>
