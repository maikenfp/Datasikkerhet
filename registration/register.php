<?php
require_once "config/Database.php";

$database = new Database();
$db = $database->connect();

if(isset($_REQUEST["stud_reg"])) {
    $username = strip_tags($_REQUEST["navn"]);
    $email = strip_tags($_REQUEST["e-post"]);
    $password = strip_tags($_REQUEST["passord"]);
    $course = strip_tags($_REQUEST["studieretning"]);
    $year = strip_tags($_REQUEST["studiekull"]);

    if(empty($username)) {
        $errorMsg[] = "legg til navn";
    }
    else if(empty($email)) {
        $errorMsg[] = "legg til epost";
    }
    /* Om vi vil ha validering på epost, dette påvirker sikkerhet, 
        så tar det ikke med. 
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errorMsg[]="Skriv inn en valid epost";
    }
    */
    else if(empty($password)) {
        $errorMsg[] = "skriv inn passord";
    }
    else if(empty($course)) {
        $errorMsg[] = "velg studieretning";
    }
    else if(empty($year)) {
        $errorMsg[] = "velg studiekull";
    }
    
    else {
        try {
            $select_stmt=$db->prepare("SELECT navn,`e-post`,passord,studieretning,studiekull FROM student 
            WHERE navn=:uname OR `e-post`=:uemail");

            $select_stmt->execute(array("uname"=>$username, ":uemail"=>$email, 
                "upassword"=>$password, "ustudieretning"=>$course, "ustudiekull"=>$year));
            $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
           /*
            Om vi vil ha en "check" om epost allerede er i bruk.  
            if($row["email"]==$email){
                $errorMsg[]="Epost er allerede i bruk";
            }
            */ 
            if(!isset($errorMsg)) {
                $insert_stmt=$db->prepare("INSERT INTO student (navn,`e-post`,passord,studieretning,studiekull) 
                    VALUES (:uname,:uemail,:upassord,:ustudieretning,:ustudiekull");
            }

            if($insert_stmt->execute(array( "uname" => $username,
                                            "uemail" => $email, 
                                            "upassord" => $password, 
                                            "ustudieretning" => $course, 
                                            "ustudiekull" => $year))) {
                $registerMsg="Register Successfull";
            }
        }
        catch(PDOException $e) {
            echo $e -> getMessage();
        }
    }
}

if(isset($_REQUEST["fore_reg"])) {
    $username = strip_tags($_REQUEST["navn"]);
    $email = strip_tags($_REQUEST["e-post"]);
    $password = strip_tags($_REQUEST["passord"]);
    $course = strip_tags($_REQUEST["emne_id"]);

    if(empty($username)) {
        $errorMsg[] = "legg til navn";
    }
    else if(empty($email)) {
        $errorMsg[] = "legg til epost";
    }
    /* Om vi vil ha validering på epost, dette påvirker sikkerhet, 
        så tar det ikke med. 
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errorMsg[]="Skriv inn en valid epost";
    }
    */
    else if(empty($password)) {
        $errorMsg[] = "skriv inn passord";
    }
    else if(empty($course)) {
        $errorMsg[] = "velg emne";
    }
    else {
        try {
            $select_stmt=$db->prepare("SELECT username, epost, passord, emne_id FROM foreleser 
            WHERE username=:uname OR epost=:uemail");

            $select_stmt->execute(array("uname"=>$username, ":uemail"=>$email, 
                "upassword"=>$password, "uemne_id"=>$course));
            $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
            /*
            Om vi vil ha en "check" om epost allerede er i bruk.  
            if($row["email"]==$email){
                $errorMsg[]="Epost er allerede i bruk";
            }
            */
            if(!isset($errorMsg)) {
                $insert_stmt=$db->prepare("INSERT INTO student (username,email,passord,studieretning) 
                    VALUES (:uname,:uemail,:upassword,:uemne_id");
            }

            if($insert_stmt->execute(array( "uname" => $username,
                                            "uemail" => $email, 
                                            "upassword" => $passord, 
                                            "uemne_id" => $course))) {
                $registerMsg="Register Successfull";
            }
        }
        catch(PDOException $e) {
            echo $e -> getMessage();
        }
    }
}
?>