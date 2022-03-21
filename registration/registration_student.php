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
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);


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
        $query = "SELECT epost FROM student WHERE epost = '$email'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $row_count = $stmt->fetch();

        if($row_count > 0) {
            header("Location: student.php?error=Eposten er allerede i bruk!");
            exit();
        }

        else{
            $sql = "INSERT INTO student (navn, epost, passord, studieretning, studiekull)
            VALUES (:uname, :uemail, :upassord, :ustudieretning, :ustudiekull)";

            $insert_stmt = $db->prepare($sql);
            $insert_stmt->bindParam(":uname", $username);
            $insert_stmt->bindParam(":uemail", $email);
            $insert_stmt->bindParam(":upassord", $pass_hash);
            $insert_stmt->bindParam(":ustudieretning", $course);
            $insert_stmt->bindParam(":ustudiekull", $year);

            $insert_stmt->execute(array(
                    ":uname" => $username,
                    ":uemail" => $email,
                    ":upassord" => $pass_hash,
                    ":ustudieretning" => $course,
                    ":ustudiekull" => $year));

            $_SESSION['student_id'] = $db->lastInsertId();
            $_SESSION['studieretning'] = $course;
            $_SESSION['studiekull'] = $year;
            $_SESSION['epost'] = $email;
            $_SESSION['navn'] = $username;

            $id = $db->lastInsertId();

            header("Location: ../student/index.php");
            exit();
        }
    }
}
