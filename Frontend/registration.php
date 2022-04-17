<?php
//include config file
    require_once config.php;

    //defining variables
    $username = $password = $confirm_password = $username_error = $password_error = $confirm_password_error = $email = $email_error = "";

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
    
        //checking for errors before inserting
        if(empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($email_error)){
            //preparing insert statement
            $sql = "INSERT INTO user (username, password, email) VALUES (:username, :password, :email)";

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
      }      
?>
/* <?php
include ('config.php');
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$confirm_password = trim($_POST['confirm_password']);
$password = trim($_POST['password']);
if ((isset($username) && !empty($username)) && (isset($email) && !empty($email)) && (isset($confirm_password) && !empty($confirm_password)) && (isset($password) && !empty($password))) {
    $query = "insert into user (username, email, password) values ('$username', '$email', '$password')";
    $result = pg_query($query);
    if (!$result) {
        $errormessage = pg_last_error();
        echo "Error with query: " . $errormessage;
    } else {
        echo "User Registration Successfull!!!";
    }
} else {
    echo "Invalid input. Please enter all the input fields in form";
}
?> */