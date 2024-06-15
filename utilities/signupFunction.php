<?php
    //Hide PHP errors from being displayed
    error_reporting(0);

    include ("connection.php");

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = htmlspecialchars($_POST['email']);
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        try {
            //Test if email is already taken
            
            //Prepare statements
            $statement = $conn -> prepare("SELECT * FROM users WHERE email = ?");
            $statement -> bind_param("s", $email);

            
            //Execute and get results
            $statement -> execute();
            $result = $statement->get_result();

            //If email already exists in database
            if ($result->num_rows > 0) {
                echo "Email already associated with an account. Please try again.";
            } else {
                //Test if username is already taken

                //Prepare statements
                $statement2 = $conn -> prepare("SELECT * FROM users WHERE username = ?");
                $statement2 -> bind_param("s", $username);

                //Execute and get results
                $statement2 -> execute();
                $result2 = $statement2->get_result();

                if ($result2 ->num_rows > 0) {
                    echo "Username already taken. Please try again.";
                } else {
                    //Create account

                    //Hash password
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    
                    //Prepare statements
                    $statement3 = $conn -> prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
                    $statement3 -> bind_param("sss", $email, $username, $hashedPassword);

                    //Execute
                    $statement3 -> execute();

                    if ($statement3) {
                        echo "Account successfully created. Please log in.";
                    }
                    else {
                        echo "Account creation failed. Please try again.";
                    }
                }
                $statement -> close();
                $statement2 -> close();
                $statement3 -> close();
            }
            

        } catch (Exception $e) {
            echo "Signup error.";
        }

        //Close connection
        $conn -> close();

    }
?>