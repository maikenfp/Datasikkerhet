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
            <form action="" method="post">
                <label for="pinkode">Pin-kode:</label>
                <input type="number" name="pin" min="0" max="9999" >
                <button type="submit" value="Submit">Søk</button>    
            </form>

            <?php
            
                $dbhost = "localhost";
                $dbuser = "test";
                $dbpass = "test123";
                $db = "datasikkerhet";
                $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
           
                $sql = "SELECT * FROM emne where pinkode = $_POST[pin]";

                

                $result = $conn->query($sql);  
                
            
                if ($result->num_rows > 0){
                    //output all emne info from db
                    while($row = $result->fetch_assoc()) {
                        echo "<br> Emnekode: " . $row["emnekode"]. "<br>Emnenavn: " . $row["emnenavn"]. 
                        "<br>Pin-kode: " . $row["pinkode"]. " <br>";
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




