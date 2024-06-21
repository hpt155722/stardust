<?php
    // Uncomment to hide PHP errors from being displayed
    // error_reporting(0);

    // Include database connection file
    include("connection.php");

    session_start();

    if (isset($_POST['image'])) {
        $imageData = $_POST['image'];

        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        
        $decodedImage = base64_decode($imageData);

        if ($decodedImage !== false) {
            // Delete old profile picture if it exists
            $loggedInUser = $_SESSION['loggedInUser'];
            $sql = "SELECT username, profilePic FROM users WHERE userID = '$loggedInUser'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $username = $row['username'];
                $oldProfilePic = $row['profilePic'];

                if (!empty($oldProfilePic)) {
                    $oldFilePath = '../resources/profilePics/' . $oldProfilePic;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath); // Delete old file
                    }
                }

                // Save the new image to the server
                $filename = $username . '-' . $loggedInUser . '-profilePicture.png';
                $targetDir = '../resources/profilePics/';
                $targetPath = $targetDir . $filename;

                if (file_put_contents($targetPath, $decodedImage)) {
                    // Update profile picture in database
                    $sql = "UPDATE users SET profilePic = '$filename' WHERE userID = '$loggedInUser'";

                    if ($conn->query($sql) === TRUE) {
                        // Query executed successfully
                        echo "Profile picture updated successfully";
                    } else {
                        // Error in query execution
                        echo "Error updating profile picture: " . $conn->error;
                    }
                } else {
                    // Error saving image
                    echo "Failed to save the image.";
                }
            } else {
                // User not found
                echo "User not found in database.";
            }
        } else {
            // Invalid base64 format
            echo "Invalid base64 format.";
        }

        // Close the database connection
        $conn->close();
    } else {
        // No image data received
        echo "No image data received.";
    }
?>
