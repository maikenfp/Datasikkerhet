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

                // Forms actions:
                if (isset($_POST["PinButton"])){
                   getEmneInfo($_POST["pinkode"]);
                   showMessage($_POST["pinkode"]);
                }
            
                if (isset($_POST["button"])){
                    $pin = "[pin]";
                    commentMessage($_POST["kommenter"], $_POST["meldingID"]);
                    showMessage($_POST["pin"]);
                }
                
                if(isset($_POST['rapporter'])){
                    $id = "[report]";
                    $pin = "[pin]";
                    reportMessage($_POST["report"]);
                    showMessage($_POST["pin"]);
                }
            ?>

        </main>
    </body>
</html>

<?php

function sqlQuery($sql){
    $database = new Database();
    $db = $database->connect();
    $stmt = $db->query($sql);
    return $stmt;
}

function fetchArray($sql){
    $stmt = sqlQuery($sql);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}

function getEmneInfo($pin){
    $sql ="SELECT emne.emnekode, emne.emnenavn, emne.pinkode 
        FROM emne WHERE pinkode='$pin' limit 1";
    $row = sqlQuery($sql);

    if($row){
       echo "Emne info:"; 
        foreach ($row as $row) {
            echo "<br>Emnekode:". $row["emnekode"]. "<br>Emnenavn: " . $row["emnenavn"]. "<br>Pin-kode: " . $row["pinkode"];
        }
    }
}

function getMessage($pin){
    $sql = "SELECT emne.emnekode, emne.emnenavn, emne.pinkode, 
         melding.svar, melding.spørsmål, melding.melding_id
         FROM emne INNER JOIN melding ON emne.emne_id = melding.emne_id WHERE pinkode='$pin'";
    $row = sqlQuery($sql);
    return $row;
}

function getComment($meldingId){  
    $sql = "SELECT kommentar.kommentar_id, kommentar.melding_id, kommentar.kommentar FROM kommentar WHERE melding_id='$meldingId'";
    $row = sqlQuery($sql);
    return $row;
}

function showMessage($pin){
    $row = getMessage($pin);
    if($row){
        //Spørsmål and svar:
        foreach ($row as $row) {
            echo "<br><br> Spørsmål: ". $row["spørsmål"]; 
                if (!empty($row["svar"])){ 
                    echo "<br> Svar: ". $row["svar"];
                }

            //Kommentar:
            $comment = getComment($row["melding_id"]);
            if ($comment);
                foreach ($comment as $comment){
                    echo "<br> Kommentar: ";
                    echo $comment["kommentar"];  
            }

            ?>
            <!-- Send kommentar -->
            <form method="POST">
                <input type="hidden"  name='meldingID' value="<?php echo $row['melding_id']?>">
                <input type="hidden" name="pin" value="<?php echo $pin?>">
                <label>Kommentar:</label>
                <textarea name="kommenter"></textarea>
                <input type="submit" name="button" value="Svar">
            </form>

            <!-- Rapporter melding -->
            <form method="POST">
                <input type="hidden" name='report' value="<?php echo $row['melding_id']?>">
                <input type="hidden" name="pin" value="<?php echo $pin?>">
                <input type="submit" name="rapporter" value="Rapporter">
            </form>
            <?php
              
        }
    }
}

function commentMessage($kommentar, $melding_id){
    $sql = "INSERT INTO kommentar (kommentar, melding_id) 
            VALUES ('$kommentar', '$melding_id')";
    sqlQuery($sql);
}

function reportMessage($id){
    $sql = "UPDATE melding SET upassende_melding = COALESCE(upassende_melding)+1 WHERE melding_id = $id";
    sqlQuery($sql);
}
?>