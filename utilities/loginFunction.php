<?php
    //Hide PHP errors from being displayed
    //error_reporting(0);

    include "connection.php";

    session_start();
    echo "Request method: " . $_SERVER["REQUEST_METHOD"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);

        try {
            //Prepare statements
            $statement = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $statement->bind_param("s", $username);

            //Execute and get results
            $statement->execute();
            $result = $statement->get_result();

            //See if user exists
            if ($result->num_rows == 1) {
                //Get row data
                $selectedUser = $result->fetch_assoc();

                //Verify password
                $storedHashedPassword = $selectedUser["password"];
                if (password_verify($password, $storedHashedPassword)) {
                    //Get user ID
                    $userID = $selectedUser["userID"];

                    //Store userID in sesiion variable
                    $_SESSION["loggedInUser"] = $userID;
                    $_SESSION["loggedInUsername"] = $username;

                    //Login Successful
                    echo "Logged in successfully.";
                } else {
                    echo "Invalid credentials. Please try again.";
                }
            } else {
                //Login Failed
                echo "Invalid credentials. Please try again.";
            }
            $statement->close();
        } catch (Exception $e) {
            echo "Login error: " . $e->getMessage(); // Echo the error message for debugging
        }

        //Close connection
        $conn->close();
    }
?>
