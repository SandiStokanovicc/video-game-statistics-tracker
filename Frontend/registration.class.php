<?php
class Registration{
public function register($requestData){
//include config file
    require_once '/../Backend/code/rest/config.php';

        //defining database credentials
        define("DB_Server", "localhost");
        define("DB_Username", "root");
        define("DB_Password", "root");
        define("DB_Name", "riot");
        //connecting to mysql database
        try{
        $pdo = new PDO("mysql:host=" . DB_server . ";dbname=" . DB_Name, DB_Username, DB_Password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
        die("Could not connect" . $e->getMessage());
        }

    //defining variables
    $username = $password = $confirm_password = $username_error = $password_error = $confirm_password_error = $email = $email_error = "";

    //if($_SERVER["REQUEST_METHOD"] == "POST"){
        //validating email
        if(empty(trim($requestData["email"]))){
            $email_error = "Please enter an email";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = "Invalid email format";
        } else{
            $sql = `SELECT id FROM user WHERE email = :email`;
            if($stmt = $pdo->prepare($sql)){
                //bind variables to the statement
                $stmt -> bindParam(":email", $param_email, PDO::PARAM_STR);
                //set parameters
                $param_email = trim($requestData["email"]);
                if(stmt->execute()){
                    if(stmt ->rowCount() == 1){
                        $email_error = "Email already taken";
                    } else {
                        $email = trim($requestData["email"]);
                    }
                } else{
                    echo "Something went wrong.";
                }
                unset($stmt);
            }
        }

        //validating username;
        if(empty(trim($requestData["username"]))){
            $username_error = "Please enter your username";
        } elseif (!preg_match(`/^[a-zA-Z0-9_]+$/`, trim($requestData["username"]))) {
            $username_error = "The username can only contain letters numbers and underscores.";
        } else {
            $sql = `SELECT id From user WHERE username = :username`;
            if($stmt = $pdo->prepare($sql)){
                //variales to the statement
                $stmt -> bindParam(":username", $param_username, PDO::PARAM_STR);
                //set parameters
                $param_username = trim($requestData["username"]);
                if(stmt->execute()){
                    if($stmt -> rowCount() == 1){
                        $username_error = "Username already taken"; 
                    } else {
                        $username = trim($requestData["username"]);
                    }
                } else{
                    echo "Something went wrong.";
                }
                unset($stmt);
            }
        }
        //validating password
        if(empty(trim($requestData["password"]))){
            $password_error = "Please enter a password.";
        }elseif (strlen(trim($requestData["password"]))<6 || strlen(trim($requestData["password"]))>32){
            $password_error = "The password must be between 6 and 32 characters long";
        }else{
            $password = trim($requestData["password"]);
        }
        //validating confirm password
        if(empty(trim($requestData["confirm_password"]))){
            $confirm_password_error = "Please confirm password.";     
        } else{
            $confirm_password = trim($requestData["confirm_password"]);
            if(empty($password_error) && ($password != $confirm_password)){
                $confirm_password_error = "The passwords do not match";
            }
        }
    
        //checking for errors before inserting
        if(empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($email_error)){
            //preparing insert statement
            $sql = `INSERT INTO user (username, ¸password, email) VALUES (:username, :password, :email)`;

            if($stmt = $pdo ->prepare($sql)){
                //bind variables to statement as parameters
                $stmt -> bindParam(":username", $param_username, PDO::PARAM_STR);
                $stmt -> bindParam(":password", $param_password, PDO::PARAM_STR);
                $stmt -> bindParam(":email", $param_email, PDO::PARAM_STR);
                
                //set params
                $param_username = $username;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); //creates password hash using bcrypt algorithm + randomly generated salt
                //execute statement
                if($stmt->execute()){
                    header("location: ?.php"); //replace later
                } else {
                    echo "Something went wrong";
                }
                //closing statement
                unset($stmt);
            }
        } 
        //close connection
        unset($pdo);
        echo "hljeb";
      } } 
?>