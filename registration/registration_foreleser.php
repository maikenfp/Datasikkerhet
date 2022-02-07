<?php
session_start();
include "../config/Database.php";

$database = new Database();
$db = $database->connect();

if(isset($_POST["fore_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php
    $imgFile = $_FILES['picture']['name'];
    $tmp_dir = $_FILES['picture']['tmp_name'];
    $imgSize = $_FILES['picture']['size'];
    if(!empty($imgFile)){

        $upload_dir = '../photos/';

        $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));

        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
        $coverpic = rand(1000,1000000).".".$imgExt;

        if(in_array($imgExt, $valid_extensions)){
            if($imgSize < 5000000){
                move_uploaded_file($tmp_dir,$upload_dir.$coverpic);
                echo "Opplasting ferdig";
            }
            else{
                $errMSG = "Filen er for stor";
            }
        }
        else{
            $errMSG = "Kun JPG, JPEG, PNG & GIF filer tillatt";
        }
    }
    $username = strip_tags($_POST["navn"]);
    $email = strip_tags($_POST["epost"]);
    $password = strip_tags($_POST["passord"]);
    $course = strip_tags($_POST["studieretning"]);
    $sv1 = strip_tags($_POST["sv1"]);
    $sv2 = strip_tags($_POST["sv2"]);
    $sp1 = strip_tags($_POST["sp1"]);
    $sp2 = strip_tags($_POST["sp2"]);
    
    $pic = $coverpic;

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
        $query = "SELECT epost FROM foreleser WHERE epost = '$email'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $row_count = $stmt->fetch();

        if($row_count > 0) {
            header("Location: foreleser.php?error=Eposten er allerede i bruk!");
            exit();
        }
        else {
            $sql= "INSERT INTO foreleser (navn,epost,passord,glemt_question_1,glemt_svar_1,glemt_question_2,glemt_svar_2,bilde_navn)
                VALUES (:uname,:uemail,:upassord, (SELECT question FROM question WHERE question_id = :sp1), :sv1, 
                (SELECT question FROM question WHERE question_id =:sp2), :sv2, :bilde_navn)";

            $insert_stmt = $db->prepare($sql);
            $insert_stmt->bindParam(":uname", $username);
            $insert_stmt->bindParam(":uemail", $email);
            $insert_stmt->bindParam(":upassord", $password);
            $insert_stmt->bindParam(":sp1", $sp1);
            $insert_stmt->bindParam(":sv1", $sv1);
            $insert_stmt->bindParam(":sp2", $sp2);
            $insert_stmt->bindParam(":sv2", $sv2);
            $insert_stmt->bindParam(":bilde_navn", $pic);

            $insert_stmt->execute(array(
                    ":uname" => $username,
                    ":uemail" => $email,
                    ":upassord" => $password,
                    ":sp1" => $sp1,
                    ":sv1" => $sv1,
                    ":sp2" => $sp2,
                    ":sv2" => $sv2,
                    ":bilde_navn" => $pic));


            $_SESSION['foreleser_id'] = $db->lastInsertId();
            $_SESSION['epost'] = $email;
            $_SESSION['navn'] = $username;
            $_SESSION['bilde_navn'] = $pic;

            $id = $db->lastInsertId();

            $stmt2 = $db->prepare("INSERT INTO foreleser_emne (foreleser_id, emne_id) VALUES ('$id', '$course')");
            $stmt2->execute();

            header("Location: ../teacher.php");
            exit();
        }

    }
}
