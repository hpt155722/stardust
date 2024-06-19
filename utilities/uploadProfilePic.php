<?php
    // Uncomment to hide PHP errors from being displayed
    // error_reporting(0);

    // Include database connection file
    include("connection.php");

    session_start();

    if (isset($_POST['image'])) {
        $imageData = $_POST['image'];

        // Remove the data:image/png;base64, part
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        
        $decodedImage = base64_decode($imageData);

        if ($decodedImage !== false) {
            $filename = uniqid('profile_pic_') . '.png';
            $targetDir = '../resources/profilePics/';
            $targetPath = $targetDir . $filename;

            // Save the image to the server
            if (file_put_contents($targetPath, $decodedImage)) {
                // Image saved successfully
                $loggedInUser = $_SESSION['loggedInUser'];
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
