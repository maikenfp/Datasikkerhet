<?php

session_start();
include '../config/Database.php';

$database = new Database();
$db = $database->connect();

$fid = $_SESSION['foreleser_id'];


if(empty($fid)){
    header('Location: ../index.php');
} else {
        
    if(isset($_POST['passord']) && isset($_POST['nyttpassord'])){

            function validate($data){
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
            return $data;
     }

    $passord = validate($_POST['passord']);
    $nyttpassord = validate($_POST['nyttpassord']);


    if (empty($passord)) {
        header("Location: change_foreleser.php?error=Du må skrive inn gammelt passord!");
        exit();
    }  
    else if(empty($nyttpassord)){
        header("Location: change_foreleser.php?error=Du må skrive inn nytt passord!");
        exit();
    }  else {

        
            $sql = "SELECT passord, foreleser_id FROM foreleser WHERE foreleser_id='$fid'";
            $stmt= $db->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row){
    
                if(intval($row['student_id']) == $sid && $row['passord'] == $passord){
        
                    $query = "UPDATE foreleser SET passord='$nyttpassord' WHERE foreleser_id='$fid'";
                    $change = $db->query($query);
            
                    echo "<script>";
                    echo "alert('Passordet er endret!');";
                    echo "</script>";
                    echo "<meta http-equiv='refresh' content='0;url=../teacher.php'>";
                    exit();
                }
                else{
                    header("Location: change_foreleser.php?error=Feil passord");
                    exit();
                }
            }
            else{
                header("Location: change_foreleser.php?error=Feil passord");
                exit();
            }
        }
    }
        else {
            header("Location: ../teacher.php");
            echo "<script>";
            echo "alert('Noe gikk galt');";
            echo "</script>";
            echo "<meta http-equiv='refresh' content='0;url=../teacher.php'>";	
            exit();
        
}
}