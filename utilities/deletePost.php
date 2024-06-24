<?php
    // Include database connection file
    include("connection.php");

    if (isset($_GET['postID'])) {
        $postID = $_GET['postID'];

        // Select imageFilePath from posts table
        $sql_select = "SELECT imageFilePath FROM posts WHERE postID = ?";
        $stmt_select = $conn->prepare($sql_select);

        if ($stmt_select) {
            // Bind parameter
            $stmt_select->bind_param('i', $postID);

            // Execute statement
            if ($stmt_select->execute()) {
                // Bind result variables
                $stmt_select->bind_result($imageFilePath);

                // Fetch the result
                if ($stmt_select->fetch()) {
                    // Delete image file
                    if (file_exists('../resources/posts/'.$imageFilePath)) {
                        unlink('../resources/posts/'.$imageFilePath);
                        echo "Image file deleted successfully<br>";
                    } else {
                        echo "Image file not found: ../resources/posts/".$imageFilePath;
                    }
                } else {
                    echo "Failed to fetch image file path<br>";
                }

                // Close statement
                $stmt_select->close();
            } else {
                echo "Error executing select statement<br>";
            }
        } else {
            echo "Prepare select statement failed<br>";
        }

        // Now delete the post from the database
        $sql_delete = "DELETE FROM posts WHERE postID = ?";
        $stmt_delete = $conn->prepare($sql_delete);

        if ($stmt_delete) {
            // Bind parameter
            $stmt_delete->bind_param('i', $postID);

            // Execute statement
            if ($stmt_delete->execute()) {
                // Delete successful
                echo "Post deleted successfully<br>";
            } else {
                // Delete failed
                echo "Error deleting post<br>";
            }

            // Close statement
            $stmt_delete->close();
        } else {
            echo "Prepare delete statement failed<br>";
        }

        // Close database connection
        $conn->close();
    } else {
        // If postID is not received
        echo "postID parameter not provided";
    }
?>
