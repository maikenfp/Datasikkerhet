<!DOCTYPE html>
<html lang="NB-NO">
    <head>
        <meta charset="utf-8">
        <title>Emne tilbakemelding | Gjestebruker</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- dimensions and scaling for the browser -->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Tilbakemeldinger for emner</h1>
        </header>
        <main>
            <form action="" method="post">
                <label for="pinkode">Pin-kode:</label>
                <input type="number" name="pin" min="0" max="9999" >
                <button type="submit">Søk</button>    
            </form>

            <?php
                include_once "config/Database.php";

                $database = new Database();
                $db = $database->connect();

                $sql = "SELECT emne.emnekode, emne.emnenavn, emne.pinkode, 
                    melding.svar, melding.spørsmål
                    FROM emne INNER JOIN melding ON emne.emne_id = melding.emne_id WHERE pinkode = '$_POST[pin]'";

                $stmt = $db->query($sql);
                $result = $db->query($sql);
                $row_count = $result->fetchColumn();

                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($row){

                    foreach ($row as $row) {
                        echo "<br>Emnekode:". $row["emnekode"]. "<br>Emnenavn: " . $row["emnenavn"]. "<br>Pin-kode: " . $row["pinkode"];
                        echo "<br><br>Spørsmål: " . $row["spørsmål"]. "<br> Svar: ". $row["svar"]. "<br>";
                        ?>

                        <!-- Submit comment -->
                        <form action="" method="post">
                            <textarea name="Kommenter" rows="4" cols="28">
                            </textarea>
                            <button type="submit">Kommenter</button>
                        </form>
                        <?php
                        
                    }
                } 
                
                else {
                    echo "Ingen resultat";
                }

            ?>
        </main>
    </body>
</html>




