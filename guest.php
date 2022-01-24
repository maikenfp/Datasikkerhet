<!DOCTYPE html>
<html lang="NB-NO">
    <head>
        <meta charset="utf-8">
        <title>Hiof tilbakemelding | Gjestebruker</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- dimensions and scaling for the browser -->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Tilbakemeldinger for fag på Høgskolen i Østfold </h1>
        </header>
        <main>
            <form action="pin_get.php" method="get" id="pinkodeForm">
                <label for="pinkode">Pin-kode:</label>
                <input type="number" min="0" max="9999" >
                <button type="submit" form="pinkodeForm" value="Submit">Søk</button>    
            </form>

            <?php
                include_once "../../config/Database.php";
                
                $name = isset($_GET['name']) ? $_GET['name'] : die();
                $database = new Database();
                $db = $database->connect();

                //Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM emne";
                $result = $conn->query($sql);

                //Emne table output
                if ($result->num_rows > 0){
                    //output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<br> Emnekode: " . $row["emnekode"]. " Emnenavn: " . $row["emnenavn"]. 
                        " Pin-kode: " . $row["pinkode"]. "Melding id:" . $row["melding_id"]. " <br>";
                    }
                } 
                // echo "</table>";
                else {
                    echo "Ingen resultat";
                }
                
                $conn->close();
            ?>
        </main>
    </body>
</html>




