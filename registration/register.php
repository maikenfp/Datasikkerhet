<?php
require_once "../config/Database.php";

$database = new Database();
$db = $database->connect();

if(isset($_POST["stud_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php
    $username = strip_tags($_POST["navn"]); // Tar infoen som står i boksene med tags som er i ["tag"]. 
    $email = strip_tags($_POST["epost"]);
    $password = strip_tags($_POST["passord"]);
    $course = strip_tags($_POST["studiekull"]);
    $year = strip_tags($_POST["studieretning"]);

    if(empty($username)) {
        $errorMsg[] = "legg til navn";
    }
    else if(empty($email)) {
        $errorMsg[] = "legg til epost";
    }
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
            if(!isset($errorMsg)) {
                $insert_stmt=$db->prepare("INSERT INTO student (navn,epost,passord,studiekull,studieretning) 
                    VALUES (:uname,:uemail,:upassord,:ustudiekull,:usint_coursetudieretning)");
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
}

if(isset($_POST["fore_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php
    $username = strip_tags($_POST["navn"]); // Tar infoen som står i boksene med tags som er i ["tag"]. 
    $email = strip_tags($_POST["epost"]);
    $password = strip_tags($_POST["passord"]);

    $course = strip_tags($_POST["emne_id"]);
    $int_course = intval($course); 

    if(empty($username)) {
        $errorMsg[] = "legg til navn";
    }
    else if(empty($email)) {
        $errorMsg[] = "legg til epost";
    }
    else if(empty($password)) {
        $errorMsg[] = "skriv inn passord";
    }

    else if(empty($int_course)) {
        $errorMsg[] = "velg studiekull";
    }

    else {
        try {
            if(!isset($errorMsg)) {
                $insert_stmt=$db->prepare("INSERT INTO foreleser (navn,epost,passord) 
                    VALUES (:uname,:uemail,:upassord)");
                if($insert_stmt->execute(array( 
                        ":uname" => $username,
                        ":uemail" => $email,
                        ":upassord" => $password))) {
                            // $registerMsg="Register Successfull";
                }
                // $insert_course_stmt=$db->prepare("INSERT INTO foreleser_emne (emne_id)
                //     VALUES (:uemne)");
                // $insert_from_emne = $db->prepare("SELECT (emne_id) FROM emne 
                //     WHERE (emne.emne_id = :uemne)");

                // $insert_from_emne = $db->prepare("INSERT INTO foreleser_emne FROM emne
                //     WHERE (emne.emne_id = :uemne)");

                $insert_from_emne = $db->prepare("INSERT INTO foreleser_emne (emne_id)
                    SELECT emne_id FROM emne WHERE emne.emne_id = :uemne") ();
                if($insert_from_emne->execute(array(
                        ":uemne" => $int_course))) {
                            // $registerMsg="Register Successfull";
                            echo "4-4";
                        }
                $insert_foreId = $db->prepare("INSERT INTO foreleser_emne (foreleser_id)
                    SELECT foreleser_id FROM foreleser");
                $insert_foreId->execute();
            } else {
                var_dump($errorMsg);
            }         
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}

?>