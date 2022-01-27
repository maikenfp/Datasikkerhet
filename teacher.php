<?php
    session_start();
    require 'config/Database.php';

    $database = new Database();
    $db = $database->connect();

    $query = "SELECT * from emne";
    $stmt = $db->query($query);
    //$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html>
    <body>
    <h1>Foreleser</h1>

    <form action="teachersubject.php" method="post">
        <label for="subject">Emne:<span class="required"></span></label>
        <select name="subject" required>
            <option disabled selected value>Velg emne</option>

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
            <button type="submit" name="submit">Go</button>
    </form>
    </body>
</html>
