<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Hiof tilbakemelding | Gjestebruker</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- dimensions and scaling for the browser -->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>Tilbakemeldinger for fag på Høgskolen i Østfold </h1>

        <?php
        echo "Hello World";
        ?>
        
        <?php
        $servername = "localhost";
        $username = "username";
        $password = "password";
        $dbname = "myDB";

        //Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        //Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT emnekode, emnenavn, pinkode, melding_id FROM emne";
        $result = $conn->query($sql);

        //Emne table output
        echo "<br> Emnekode: " . $emnekode. " Emnenavn: " . $emnenavn. " Pin-kode: " . $pinkode. " <br>";

        ?>

    </body>
</html>