<!DOCTYPE html>
<html lang="NB-NO">
    <head>
        <meta charset="utf-8">
        <title>Emne tilbakemelding | Gjestebruker</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- dimensions and scaling for the browser -->
        <!-- <link rel="stylesheet" href="style.css"> -->
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
                if (isset($_POST["PinButton"])){
                   getMessage($_POST["pinkode"]);
                }
            
             
                if (isset($_POST["button"])){
                    commentMessage($_POST["kommenter"], $_POST["meldingID"]);
                    // commentMessage($_POST["kommenter"], "$row[melding_id]");
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

        </main>
    </body>
</html>

<?php

function getMessage($pin){
    include_once "config/Database.php";
    $database = new Database();
    $db = $database->connect();

    
    $sql = "SELECT emne.emnekode, emne.emnenavn, emne.pinkode, 
        melding.svar, melding.spørsmål, melding.melding_id
        FROM emne INNER JOIN melding ON emne.emne_id = melding.emne_id WHERE pinkode='$pin'";

    
    $stmt = $db->query($sql);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    if($row){
        
        foreach ($row as $row) {
            echo "<br>Emnekode:". $row["emnekode"]. "<br>Emnenavn: " . $row["emnenavn"]. "<br>Pin-kode: " . $row["pinkode"];
            echo "<br><br> Melding_id:". $row["melding_id"]."<br> Spørsmål: ". $row["spørsmål"]. "<br> Svar: ". $row["svar"]. "<br>";
        }
    }
    else {
        echo "Ingen resultat";
    }
    
}

function commentMessage($kommentar, $melding_id){
    include_once "config/Database.php";
    echo "comment";

    $database = new Database();
    $db = $database->connect();

    $sql = "INSERT INTO kommentar (kommentar, melding_id) 
            VALUES ('$kommentar', '$melding_id')";
    $stmt = $db->query($sql);
}


?>



