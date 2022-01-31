<?php
require_once "../../config/Database.php";

$database = new Database();
$db = $database->connect();

if(isset($_POST["fore_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php
    $username = strip_tags($_POST["navn"]); 
    $email = strip_tags($_POST["epost"]);
    $password = strip_tags($_POST["passord"]);

    $course = strip_tags($_POST["emne_id"]);
    $int_course = intval($course); 

    try {
        if(!isset($errorMsg)) {
            $insert_stmt=$db->prepare("INSERT INTO foreleser (navn,epost,passord) 
                VALUES (:uname,:uemail,:upassord)");
            if($insert_stmt->execute(array( 
                    ":uname" => $username,
                    ":uemail" => $email,
                    ":upassord" => $password))) {
    
            }

            $sql = "
                INSERT INTO foreleser_emne (foreleser_id, emne_id)
                    VALUES 
                    ((SELECT MAX(foreleser_id) FROM foreleser),
                            (SELECT emne_id FROM emne 
                                WHERE emne.emne_id = $course));    
            ";

            $insert_stmt = $db->prepare($sql);
            $insert_stmt->execute(array( 
                ":uname" => $username ));

        } else {
            echo($errorMsg);
        }         
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }

}

?>