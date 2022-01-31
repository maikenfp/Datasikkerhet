<?php
include_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

if(isset($_POST['epost']) && isset($_POST['passord'])){

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
     }

    $passord = validate($_POST['passord']);
    $nyttpassord = validate($_POST['nyttpassord']);
    $epost = validate($_POST['epost']);


    if (empty($passord)) {
        header("Location: change.php?error=Du må skrive inn e-post!");
        exit();
    } else if(empty($epost)){
        header("Location: change.php?error=Du må skrive inn passord!");
        exit();
    } 
    else if(empty($nyttpassord)){
        header("Location: change.php?error=Du må skrive inn nytt passord!");
        exit();
    }  else {

            $sql = "SELECT passord FROM student WHERE epost='$epost' AND passord='$passord'";
            $stmt= $db->prepare($sql);
            $stmt->execute();
            $results = $stmt -> fetchAll(PDO::FETCH_OBJ);

            if($stmt -> rowCount() > 0){

                if($results['epost'] === $epost && $results['passord'] === $passord){
                    $query = "UPDATE student SET passord='$nyttpassord' WHERE epost='$epost'";
                    $change = $db->prepare($query);
                    $change->execute();
                    echo "<script>";
                    echo "alert('Passordet er endret!');";
                    echo "</script>";
                    echo "<meta http-equiv='refresh' content='0;url=student/index.php'>";
                    exit();
                }
                else{
                    header("Location: change.php?error=Feil brukernavn eller passord");
                    exit();
                }
            }
            else{
                header("Location: change.php?error=Feil brukernavn eller passord");
                exit();
            }
        }
    }
        else {
            header("Location: student/index.php");
            echo "<script>";
            echo "alert('Noe gikk galt');";
            echo "</script>";
            echo "<meta http-equiv='refresh' content='0;url=student/index.php'>";	
            exit();
        
}