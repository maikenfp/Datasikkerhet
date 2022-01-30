<!DOCTYPE html>
<html lang="NB-NO">
    <head>
        <meta charset="utf-8">
        <title>Emne tilbakemelding | Gjestebruker</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- dimensions and scaling for the browser -->
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h1>Tilbakemeldinger for emner</h1>
        </header>
        <main>
            <form method="post">
                <label>Pin-kode:</label>
                <input type="number" name='pinkode' min="0" max="9999" >
                <button type="submit" name="PinButton">Søk</button>    
            </form>
            <?php
                include_once "config/database.php";

                if (isset($_POST["PinButton"])){
                   getEmneInfo($_POST["pinkode"]);
                   showMessage($_POST["pinkode"]);
                }
            
                if (isset($_POST["button"])){
                    commentMessage($_POST["kommenter"], $_POST["meldingID"]);
                }

                
                if(isset($_POST['rapporter'])){
                    $id = "[report]";
                    reportMessage($_POST["report"]);
                }
            
            ?>

            <form method="POST">
                    <br>
                    <label>Kommentar:</label>
                    <br>
                    <textarea name="kommenter" >
                    </textarea>
                    <br>
                    <label>Melding_id:</label>
                    <br>
                    <input type="number" name="meldingID">
                    <button type="submit" name="button"> kommenter</button>
            </form>
            
            
            <form  method='POST'>
                <label>Rapporter melding nr.:</label>
                <input type="number" name='report' min="0" max="9999" >
                <button type="submit" name="rapporter"> Rapporter</button> 
            </form>
            

        </main>
    </body>
</html>

<?php

function getEmneInfo($pin){
    $database = new Database();
    $db = $database->connect();

    $sql ="SELECT emne.emnekode, emne.emnenavn, emne.pinkode 
        FROM emne INNER JOIN melding ON emne.emne_id = melding.emne_id WHERE pinkode='$pin' limit 1";
    
    $stmt = $db->query($sql);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    
    if($row){
       echo "Emne info:"; 
        foreach ($row as $row) {
            echo "<br>Emnekode:". $row["emnekode"]. "<br>Emnenavn: " . $row["emnenavn"]. "<br>Pin-kode: " . $row["pinkode"];
        }
    }
}


function getMessage($pin){
    $database = new Database();
    $db = $database->connect();
    
    $sql = "SELECT emne.emnekode, emne.emnenavn, emne.pinkode, 
         melding.svar, melding.spørsmål, melding.melding_id
         FROM emne INNER JOIN melding ON emne.emne_id = melding.emne_id WHERE pinkode='$pin'";

    $stmt = $db->query($sql);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
    return $row;
}

function getComment($meldingId){
    $database = new Database();
    $db = $database->connect();

    $sql = "SELECT kommentar.kommentar_id, kommentar.melding_id FROM kommentar WHERE melding_id='meldingId'";

    $stmt = $db->query($sql);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $row;
}


function showMessage($pin){
    $row = getMessage($pin);
    
    if($row){
        foreach ($row as $row) {
            echo "<br><br> Melding_id: ". $row["melding_id"]."<br> Spørsmål: ". $row["spørsmål"]; 
                if (!empty($row["svar"])){ 
                    echo "<br> Svar: ". $row["svar"]. "<br>";
                }
            $comment = getComment($row["melding_id"]);
            echo $comment;

            
            // $id = $row["melding_id"];
            // if(isset($_POST['rapporter'])){
            //     reportMessage($_POST[$id]);
            // }
            ?>
            <!-- <form  method='POST'> 
                <button type="submit" name="rapporter"> Rapporter</button> 
            </form> -->
            <?php
            
        }
    }
}

function commentMessage($kommentar, $melding_id){
    $database = new Database();
    $db = $database->connect();

    $sql = "INSERT INTO kommentar (kommentar, melding_id) 
            VALUES ('$kommentar', '$melding_id')";
    $stmt = $db->query($sql);
}

function reportMessage($id){
    $database = new Database();
    $db = $database->connect();
    $sql = "UPDATE melding SET upassende_melding = COALESCE(upassende_melding)+1 WHERE melding_id = $id";
    $stmt = $db->query($sql);
}
?>