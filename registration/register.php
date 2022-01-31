<?php
session_start();
include "../config/Database.php";

$database = new Database();
$db = $database->connect();


if(isset($_POST["stud_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php
    $username = strip_tags($_POST["navn"]); // Tar infoen som står i boksene med tags som er i ["tag"]. 
    $email = strip_tags($_POST["epost"]);
    $password = strip_tags($_POST["passord"]);
    $course = strip_tags($_POST["studieretning"]);
    $year = strip_tags($_POST["studiekull"]);

    if(empty($username)) {
        header("Location: index.php?error=Du må skrive inn navn!");
        exit();
    }
    else if(empty($email)) {
        header("Location: index.php?error=Du må skrive inn epost!");
        exit();
    }
    else if(empty($password)) {
        header("Location: index.php?error=Du må skrive inn passord!");
        exit();
    }
    else if(empty($course)) {
        header("Location: index.php?error=Du må skrive inn studeretning!");
        exit();
    }
    else if(empty($year)) {
        header("Location: index.php?error=Du må skrive inn studiekull!");
        exit();
    }

    else {
        try {
                $sql = "INSERT INTO student (navn, epost, passord, studieretning, studiekull) 
                VALUES (:uname, :uemail, :upassord, :ustudieretning, :ustudiekull)";
                $insert_stmt = $db->prepare($sql);
                $insert_stmt->bindParam(":uname", $username);
                $insert_stmt->bindParam(":uemail", $email);
                $insert_stmt->bindParam(":upassord", $password);
                $insert_stmt->bindParam(":ustudieretning", $course);
                $insert_stmt->bindParam(":ustudiekull", $year);
        
                $insert_stmt->execute(array( 
                        ":uname" => $username,
                        ":uemail" => $email,
                        ":upassord" => $password, 
                        ":ustudieretning" => $course,
                        ":ustudiekull" => $year)); 
                $_SESSION['student_id'] = $db->lastInsertId();
                $_SESSION['studieretning'] = $course;
                $_SESSION['studiekull'] = $year;
                $_SESSION['epost'] = $email;
                $_SESSION['navn'] = $username;
                 
               /* $query2 = "INSERT INTO student_emne (student_id, emne_id)
                    VALUES (SELECT student_id FROM student WHERE epost = $email, SELECT emne_id FROM emne WHERE emne_id = '$course')";
                $stmt = $db->prepare($query2);
                $stmt->execute(array(":uname" => $username));*/
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}
/*
if(isset($_POST["fore_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php
    $username = strip_tags($_POST["navn"]); 
    $email = strip_tags($_POST["epost"]);
    $password = strip_tags($_POST["passord"]);
    $course = strip_tags($_POST["emne_id"]);
    $int_course = intval($course); 

    if(empty($username)) {
        header("Location: index.php?error=Du må skrive inn navn!");
        exit();
    }
    else if(empty($email)) {
        header("Location: index.php?error=Du må skrive inn epost!");
        exit();
    }
    else if(empty($password)) {
        header("Location: index.php?error=Du må skrive inn passord!");
        exit();
    }

    else if(empty($int_course)) {
        header("Location: index.php?error=Du må skrive inn emne!");
        exit();
    }

    else {
                try {
                    if(!isset($errorMsg)) {
                        $insert_stmt=$db->prepare("INSERT INTO foreleser (navn,epost,passord) 
                            VALUES (:uname,:uemail,:upassord)");
                        $insert_stmt->execute(array( 
                                ":uname" => $username,
                                ":uemail" => $email,
                                ":upassord" => $password)); 
            
            
                        $sql = "
                            INSERT INTO foreleser_emne (foreleser_id, emne_id)
                                VALUES 
                                ((SELECT MAX(foreleser_id) FROM foreleser),
                                        (SELECT emne_id FROM emne 
                                            WHERE emne.emne_id = $course));
                        ";
            
                        $insert_stmt = $db->prepare($sql);
            
                    } else {
                        echo($errorMsg);
                    }
                }
                catch(PDOException $e) {
                    echo $e->getMessage();
                }
            
            }
}*/

?>