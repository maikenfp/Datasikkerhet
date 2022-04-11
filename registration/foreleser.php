<!DOCTYPE html>
<html>
    <head>
    <title>Registering</title>
    <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <div class="header">
            <h2>Foreleser registering</h2>
        </div>

<form action="registration_foreleser.php" method="post" enctype="multipart/form-data">
            <h3>Foreleser</h3>
            <?php if (isset($_GET['error'])) { ?>
	            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
	        <?php } ?>
        <div>
        <h4>Navn</h4>
            <input type="text" name="navn" id="navn" placeholder="Skriv inn ditt navn" >
        </div>
        <div>
        <h4>Epost</h4>
            <input type="email" name="epost" id="epost" placeholder="Skriv inn din epostadresse">
        </div>
        <div>
        <h4>Passord</h4>
            <input type="password" name="passord" id="passord" placeholder="Skriv inn ønsket passord">
        </div>
        <h4>Sikkerhetsspørsmål 1</h4>
        <div>
            <select name='sp1'>
                <option disabled selected value>Vennligst velg et sikkerhetsspørsmål</option>
            <?php
                    require '../config/Database.php';
                        $database = new Database();
                        $db = $database->connect();
                        $query = "SELECT * FROM question";

                        $stmt = $db->query($query);
                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($row) {
                            foreach ($row as $row) {
                                echo "<option value=". $row['question_id'] .">". $row['question']."</option>";
                            }
                        }
                    ?>
                
            </select>
        </div>
        <div>
            <input type="text" name="sv1" id="sv1" placeholder="svar">
        </div>
        <h4>Sikkerhetsspørsmål 2</h4>
        <div>
            <select name='sp2'>
                <option disabled selected value>Vennligst velg et sikkerhetsspørsmål</option>
            <?php
                        $database = new Database();
                        $db = $database->connect();
                        $query = "SELECT * FROM question";

                        $stmt = $db->query($query);
                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($row) {
                            foreach ($row as $row) {
                                echo "<option value=". $row['question_id'] .">". $row['question']."</option>";
                            }
                        }
                    ?>
                
            </select>
        </div>
        <div>
            <input type="text" name="sv2" id="sv2" placeholder="svar">
        </div>
        <h4>Emne</h4>
        <select name="studieretning" required>
            <option disabled selected value>Vennligst velg et emne!</option>
                <?php

                        $database = new Database();
                        $db = $database->connect();
                        $query = "SELECT * FROM emne";

                        $stmt = $db->query($query);
                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($row) {
                            foreach ($row as $row) {
                                echo "<option value=". $row['emne_id'] .">". $row['emnekode']. ' ' .$row['emnenavn']."</option>";
                            }
                        }
                    ?>
        </select>
        </div>
        <div>
            <h4>Velg bilde</h4>
        <input type="file" name="picture" id="picture">
        </div>
        <div>
            <input type="submit" name="fore_reg" id="fore_reg" value="Register">
        </div>
        
        <a href="index.php">Tilbake</a>

        </form>
    </body>
</html>
