<?php
    session_start();

    include "connection.php"; // Assuming $conn is defined here as your database connection

    function sanitize($input) {
        return htmlspecialchars(trim($input));
    }

    if (!isset($_SESSION['loggedInUser'])) {
        die('User not logged in');
    }

    $userID = $_SESSION['loggedInUser'];

    // Query the database to get the username
    $sql = "SELECT username FROM users WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
    } else {
        die('User not found'); // Handle this appropriately in your application
    }

    if (isset($_POST['image']) && isset($_POST['caption'])) {
        // Retrieve image data and caption
        $imageData = $_POST['image'];
        $caption = sanitize($_POST['caption']);

        // Decode base64 image data
        $data = explode(',', $imageData);
        $decodedImage = base64_decode($data[1]);

        if ($decodedImage !== false) {
            // Generate a unique filename
            $randomID = uniqid();
            $filename = "{$username}_{$userID}_{$randomID}.jpg"; // Adjust file extension if needed

            // Directory where images will be stored
            $uploadDirectory = '../resources/posts/';

            // Save the image to the server
            $filePath = $uploadDirectory . $filename;
            $fileSaved = file_put_contents($filePath, $decodedImage);

            if ($fileSaved !== false) {
                // Insert into database
                $sql = "INSERT INTO posts (userID, imageFilePath, caption) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $userID, $filename, $caption);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "Image uploaded and post created successfully";
                } else {
                    echo "Failed to insert post into database";
                }
            } else {
                echo "Failed to save image";
            }
        } else {
            echo "Failed to decode image data";
        }
    } else {
        echo "Image data or caption not received";
    }
?>
