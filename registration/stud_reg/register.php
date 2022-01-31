<?php
require_once "../../config/Database.php";

$database = new Database();
$db = $database->connect();

if(isset($_POST["stud_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php
    $username = strip_tags($_POST["navn"]); // Tar infoen som står i boksene med tags som er i ["tag"]. 
    $email = strip_tags($_POST["epost"]);
    $password = strip_tags($_POST["passord"]);
    $course = strip_tags($_POST["studiekull"]);
    $year = strip_tags($_POST["studieretning"]);

    try {
        if(!isset($errorMsg)) {
            $insert_stmt=$db->prepare("INSERT INTO student (navn,epost,passord,studiekull,studieretning) 
                VALUES (:uname,:uemail,:upassord,:ustudiekull,:ustudieretning)");
            if($insert_stmt->execute(array( 
                    ":uname" => $username,
                    ":uemail" => $email,
                    ":upassord" => $password,
                    ":ustudiekull" => $course, 
                    ":ustudieretning" => $year))) {
            $registerMsg="Register Successfull";
            }
        } else {
            var_dump($errorMsg);
        }         
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }

}

?>