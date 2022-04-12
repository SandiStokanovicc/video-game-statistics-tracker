<?php
//include config file
require_once config.php;

//defining variables
$username = $password = $confirm_password = $username_error = $password_error = $confirm_password_error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //validating email
    if(empty(trim($_POST["email"]))){
        $email_error = "Please enter an email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format";
    } else{
        $sql = "SELECT id FROM user WHERE email = :email";
        if($stmt = $pdo->prepare($sql)){
            //bind variables to the statement
            $stmt -> bindParam(":email", $param_email, PDO::PARAM_STR);
            //set parameters
            $param_email = trim($_POST["email"]);
            if(stmt->execute()){
                if(stmt ->rowCount() == 1){
                    $email_error = "Email already taken";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Something went wrong.";
            }
            unset($stmt);
        }
    }

    //validating username;
    if(empty(trim($_POST["username"]))){
        $username_error = "Please enter your username";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_error = "The username can only contain letters numbers and underscores.";
    } else {
        $sql = "SELECT id From user WHERE username = :username";
        if($stmt = $pdo->prepare($sql)){
            //variales to the statement
            $stmt -> bindParam(":username", $param_username, PDO::PARAM_STR);
            //set parameters
            $param_username = trim($_POST["username"]);
            if(stmt->execute()){
                if($stmt -> rowCount() == 1){
                    $username_error = "Username already taken"; 
                } else {
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Something went wrong.";
            }
            unset($stmt);
        }
    }
    //validating password
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter a password.";
    }elseif (strlen(trim($_POST["password"]))<6 || strlen(trim($_POST["password"]))>32){
        $password_error = "The password must be between 6 and 32 characters long";
    }else{
        $password = trim($_POST["password"]);
    }
    //validating confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_error = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_error) && ($password != $confirm_password)){
            $confirm_password_error = "The passwords do not match";
        }
    }
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    //checking for errors before inserting
    
}
?>