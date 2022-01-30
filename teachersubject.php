<html>

    <?php
        session_start();
        require 'config/Database.php';

        $fid = $_SESSION['foreleser_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['emneID'] = $_POST['subject'];
        }
        $emneID = $_SESSION['emneID'];


        $database = new Database();
        $db = $database->connect();

        $query = "SELECT * from emne WHERE emne_id=$emneID";
        $stmt = $db->query($query);
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
        <input type="hidden" name="emneID" value="$emneID" />

        <input type="text" name="reply" placeholder="Svar">
        <button type="submit" name="submit">Svar</button>
        </form>



    <?php
        $database = new Database();
        $db = $database->connect();
        
        
        $sql = "SELECT * from melding WHERE emne_id=$emneID AND foreleser_id=$fid";

        $stmt = $db->query($sql);
        $result = $db->query($sql);
        $row_count = $result->fetchColumn();

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($row){

            foreach ($row as $row) {
                echo "<br>Dato:". $row["dato"]. "<br>Spørsmål: " . $row["spørsmål"]. "<br>Svar: " . $row["svar"];
                $meldingID = $row['melding_id'];
                ?>

                <form action="response_send.php" method="post">
                    <textarea name="reply" rows="4" cols="28" required></textarea>
                    <input type="hidden" name="responseID" value="<?php echo $meldingID; ?>"/>
                    <input type="hidden" name="emneID" value="$emneID" />
                    <button type="submit">Svar</button>
                </form>
                <?php
                
            }
        } 
    ?>


        <a href="logout.php">Logout</a>
</html>