<?php
    // Include database connection file
    include "connection.php";

    // Start session
    session_start();

    // Check if user is logged in
    if (isset($_SESSION["loggedInUser"])) {
        try {
            // Prepare and execute query to fetch user information
            $stmt_user = $conn->prepare(
                "SELECT username, biography, profilePic, userID FROM users WHERE userID = ?"
            );

            $stmt_user->bind_param("i", $_SESSION["loggedInUser"]);

            $stmt_user->execute();

            $result_user = $stmt_user->get_result();

            // Fetch user information
            if ($result_user->num_rows > 0) {
                $user = $result_user->fetch_assoc();

                // Fetch count of rows in relationships table where followingID matches loggedInUser
                $stmt_relationships = $conn->prepare(
                    "SELECT COUNT(*) AS count FROM relationships WHERE followingID = ?"
                );

                $stmt_relationships->bind_param("i", $_SESSION["loggedInUser"]);

                $stmt_relationships->execute();

                $result_relationships = $stmt_relationships->get_result();

                $relationship_count =
                    $result_relationships->num_rows > 0
                        ? $result_relationships->fetch_assoc()["count"]
                        : 0;

                // Prepare data to return as JSON including relationship count
                $data = [
                    "username" => $user["username"],
                    "biography" => $user["biography"] ?: "no biography yet",
                    "profilePic" =>
                        $user["profilePic"] ?:
                        "../../resources/images/defaultProfilePic.png",
                    "userID" => $user["userID"],
                    "relationshipCount" => $relationship_count,
                ];

                echo json_encode($data);
            } else {
                echo json_encode(null); // No user found
            }
        } catch (mysqli_sql_exception $e) {
            // Handle mysqli Exception
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    } else {
        // User not logged in
        echo json_encode(null);
    }
?>
