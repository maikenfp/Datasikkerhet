<html>

<?php
    session_start();
    require 'config/Database.php';

    $fid = $_SESSION['foreleser_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $emneID = $_POST['subject'];

    }


    $database = new Database();
    $db = $database->connect();

    $query = "SELECT * from emne WHERE emne_id=$emneID";
    $stmt = $db->query($query);
    //$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $emnenavn = $row['emnenavn'];
?>

<h1>
    Emne: 
    <?php  
        echo $emnenavn;
    ?>
</h1>

<form action="response_send.php" method="post">
        <label for="responseID">Meldinger:<span class="required"></span></label><br>
        <select name="responseID" required>
        <option disabled selected value>Velg melding</option>

        <?php 

            $database = new Database();
            $db = $database->connect();

            $query = "SELECT * from melding WHERE emne_id=$emneID AND foreleser_id=$fid";
            $stmt = $db->query($query);
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($row) {
                foreach ($row as $row) {
                    echo "<option value=". $row['melding_id'] .">". $row['spørsmål']. "</option>";    
                }
            }

        ?>
    </select>

    <input type="text" name="reply" placeholder="Svar">
    <?php 
        
    ?>
    <button type="submit" name="submit">Svar</button>
    </form>
</html>