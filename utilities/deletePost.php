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
                        echo "Image file not found: ../resources/posts/".$imageFilePath."<br>";
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

        // Delete likes associated with the post
        $sql_delete_likes = "DELETE FROM likes WHERE postID = ?";
        $stmt_delete_likes = $conn->prepare($sql_delete_likes);

        if ($stmt_delete_likes) {
            // Bind parameter
            $stmt_delete_likes->bind_param('i', $postID);

            // Execute statement
            if ($stmt_delete_likes->execute()) {
                // Likes deleted successfully
                echo "Likes deleted successfully<br>";
            } else {
                // Delete failed
                echo "Error deleting likes<br>";
            }

            // Close statement
            $stmt_delete_likes->close();
        } else {
            echo "Prepare delete likes statement failed<br>";
        }

        // Delete comments associated with the post
        $sql_delete_comments = "DELETE FROM comments WHERE postID = ?";
        $stmt_delete_comments = $conn->prepare($sql_delete_comments);

        if ($stmt_delete_comments) {
            // Bind parameter
            $stmt_delete_comments->bind_param('i', $postID);

            // Execute statement
            if ($stmt_delete_comments->execute()) {
                // Comments deleted successfully
                echo "Comments deleted successfully<br>";
            } else {
                // Delete failed
                echo "Error deleting comments<br>";
            }

            // Close statement
            $stmt_delete_comments->close();
        } else {
            echo "Prepare delete comments statement failed<br>";
        }

        // Now delete the post from the database
        $sql_delete_post = "DELETE FROM posts WHERE postID = ?";
        $stmt_delete_post = $conn->prepare($sql_delete_post);

        if ($stmt_delete_post) {
            // Bind parameter
            $stmt_delete_post->bind_param('i', $postID);

            // Execute statement
            if ($stmt_delete_post->execute()) {
                // Delete successful
                echo "Post deleted successfully<br>";
            } else {
                // Delete failed
                echo "Error deleting post<br>";
            }

            // Close statement
            $stmt_delete_post->close();
        } else {
            echo "Prepare delete post statement failed<br>";
        }

        // Close database connection
        $conn->close();
    } else {
        // If postID is not received
        echo "postID parameter not provided<br>";
    }
?>
