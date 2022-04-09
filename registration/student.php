<!DOCTYPE html>
<html>
    <head>
    <title>Registering</title>
    <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <div class="header">
            <h2>Student registering</h2>
        </div>
<form action="registration_student.php" method="post">
        <h3>Student</h3>
        <?php if (isset($_GET['error'])) { ?>
	            <p class="error"><?php echo $_GET['error']; ?></p>
	        <?php } ?>
        <div>
            <h4>Navn</h4>
            <input type="text" name="navn" id="navn" placeholder="Skriv inn ditt navn" >
        </div>
        <div>
        <h4>Epost</h4>
            <input type="text" name="epost" id="epost" placeholder="Skriv inn din epostadresse">
        </div>
        <div>
        <h4>Passord</h4>
            <input type="password" name="passord" id="passord" placeholder="Skriv inn ønsket passord">
        </div>
        <div>
        <h4>Studieretning</h4>
        <select name="studieretning" required>
            <option disabled selected value>Vennligst velg en studieretning!</option>
                <?php
                    require '../config/Database.php';
                        $database = new Database();
                        $db = $database->connect();
                        $query = "SELECT * FROM studieretning";

                        $stmt = $db->query($query);

                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($row) {
                            foreach ($row as $row) {
                                echo "<option value=". $row['retning_id'] .">". $row['studieretning']."</option>";
                            }
                        }
                    ?>
        </select>
        </div>
        <div>
        <h4>Studiekull</h4>
            <select name="studiekull" id="studiekull">
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>
        </div>
        <div>
            <button type="submit" name="stud_reg" id="stud_reg">Register</button>
        </div>

        <a href="index.php">Tilbake</a>
</form>
    </body>
</html>
