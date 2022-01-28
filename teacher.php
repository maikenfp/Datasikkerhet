<?php
    session_start();
    require 'config/Database.php';

    $database = new Database();
    $db = $database->connect();
    $fid = $_SESSION['foreleser_id'];

    $sql = "SELECT emne_id from foreleser_emne WHERE foreleser_id='$fid'";
    $stmt = $db->query($sql);
    $result = $db->query($sql);
    $row_count = $result->fetchColumn();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($row){

        foreach ($row as $row) {
            $eid = $row['emne_id'];
            echo $eid;
    }
} 

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
            
            $query = "SELECT * FROM emne WHERE emne_id=$eid";
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
    <?php
        echo $_SESSION['foreleser_id'];
    ?>
    </body>
</html>