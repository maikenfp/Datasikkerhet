<?php
session_start();
include "../config/Database.php";

$database = new Database();
$db = $database->connect();

if(isset($_POST["fore_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php
    $username = strip_tags($_POST["navn"]);
    $email = strip_tags($_POST["epost"]);
    $password = strip_tags($_POST["passord"]);
    $course = strip_tags($_POST["studieretning"]);

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
        header("Location: index.php?error=Du må skrive inn emne!");
        exit();
    }

    else {
        try {
            $sql= "INSERT INTO foreleser (navn,epost,passord)
                VALUES (:uname,:uemail,:upassord)";

            $insert_stmt = $db->prepare($sql);
            $insert_stmt->bindParam(":uname", $username);
            $insert_stmt->bindParam(":uemail", $email);
            $insert_stmt->bindParam(":upassord", $password);

            $insert_stmt->execute(array(
                    ":uname" => $username,
                    ":uemail" => $email,
                    ":upassord" => $password));


            $_SESSION['foreleser_id'] = $db->lastInsertId();
            $_SESSION['epost'] = $email;
            $_SESSION['navn'] = $username;

            $id = $db->lastInsertId();

            $stmt2 = $db->prepare("INSERT INTO foreleser_emne (foreleser_id, emne_id) VALUES ('$id', '$course')");
            $stmt2->execute();

            header("Location: ../teacher.php");
            exit();
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }

    }
}
