<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="NB-NO">

<head>
    <meta charset="utf-8">
    <title>Emne tilbakemelding | Gjestebruker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- dimensions and scaling for the browser -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Tilbakemeldinger for emner</h1>
    </header>
    <main>
        <form method="post">
            <label>Pin-kode:</label>
            <input type="number" name='pinkode' min="0" max="9999" required>
            <button type="submit" name="PinButton">Søk</button>
        </form>
        <a href="./logout.php">Logg ut</a>
        <?php
        if (isset($_SESSION["foreleser_id"])) {
            ?>
            <a href="./teacher.php">Velg emne</a><?php
        }
        if (isset($_SESSION["student_id"])) {
            ?>
            <a href="./student/index.php">Student Melding</a><?php
        }

        ?>
        <section id="section_guest">
        <?php
        // When guest user comment on message, sudent_id = NULL
        $currentStudentId = 0;
        if (session_id() != '') {
            //session has started
            if (isset($_SESSION["student_id"])) {
                $currentStudentId = $_SESSION["student_id"];
            }
        }


        echo "<br>";

        include_once "config/Database.php";

        // Forms actions:
        // Pin-kode form:
        if (isset($_POST["PinButton"])) {
            usleep(800000);
            if(!preg_match("/^[0-9]*$/", $_POST["PinButton"]))  
            {  
                    // $name_error = "<p>Feil input type</p>";  
            }
            else{ 
                getEmneInfo($_POST["pinkode"]);
                getForeleserBilde($_POST["pinkode"]);
                showMessage($_POST["pinkode"]);
            }
        }

        if (isset($_POST["button"])) {
            $pin = "[pin]";
            getEmneInfo($_POST["pin"]);
            getForeleserBilde($_POST["pin"]);

            // Input validation
            
            if(!preg_match("/^[a-zA-Z.!?,: 0-9 ]*$/", $_POST["kommenter"]))  
            {  
                    // $name_error = "<p>Feil input type</p>";  
            } 
            else{
                if ($currentStudentId >= 1) {
                    //IF Student:
                    commentMessage($_POST["kommenter"], $_POST["meldingID"], $_POST["studentID"]);
                } else {
                    //IF guest:
                    commentMessage($_POST["kommenter"], $_POST["meldingID"], 0);
                }
                showMessage($_POST["pin"]);

            }  
        }

        if (isset($_POST['rapporter'])) {
            $id = "[report]";
            $pin = "[pin]";
            getEmneInfo($_POST["pin"]);
            getForeleserBilde($_POST["pin"]);
            reportMessage($_POST["report"]);
            showMessage($_POST["pin"]);
        }
        ?>
        </section>
    </main>
</body>

</html>

<?php

function getForeleserBilde($pin){
    $emneID = getEmnekode($pin);

    $sql = "SELECT bilde_navn, navn FROM foreleser f 
    JOIN foreleser_emne fe on fe.foreleser_id = f.foreleser_id WHERE emne_id = '$emneID'";
    // $sql = "SELECT bilde_navn, navn FROM foreleserBilde JOIN foreleser_emne fe on fe.foreleser_id = f.foreleser_id WHERE emne_id = '$emneID'";

    $row = fetchArray($sql);
    if ($row){
        foreach ($row as $row){
            $bilde_navn = $row["bilde_navn"];
            ?>
            <img class="foreleser_bilde" src='photos/<?php echo $bilde_navn?>' width="250" height="250">
            <?php
        }
    }
}

function sqlQuery($sql){
    $database = new Database();
    $db = $database->connect();
    // $db = $database->connectGjest();
    $stmt = $db->query($sql);
    return $stmt;
}

function fetchArray($sql){
    $stmt = sqlQuery($sql);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}

function getEmneInfo($pin){
    // $sql = "SELECT emne.emnekode, emne.emnenavn, emne.pinkode 
    //     FROM emne WHERE pinkode='$pin' limit 1";
    $sql = "SELECT emnekode, emnenavn, pinkode FROM `emneInfo` WHERE pinkode='$pin' limit 1";
    // $sql = "SELECT * FROM `emneInfo` WHERE pinkode='$pin' limit 1";
    $row = sqlQuery($sql);

    
    

    if ($row) {
        echo "<h2> Emne info: </h2>";
        foreach ($row as $row) {
            echo "<br>Emnekode:" . $row["emnekode"] . "<br>Emnenavn: " . $row["emnenavn"] . "<br>Pin-kode: " . $row["pinkode"];
        }
    }
}


function getEmnekode($pin){
    if(!preg_match("/^[0-9]*$/", $pin)){
        // Feil input
    }
    else{
        $sql = "SELECT emne.emne_id 
            FROM emne WHERE pinkode='$pin' limit 1";
            // FROM emne WHERE pinkode=('" . $pin . "') limit 1";
        // $sql = "SELECT * FROM `emneKode` WHERE pinkode='$pin' limit 1";
        $row = sqlQuery($sql);
        if ($row){
            foreach ($row as $row){
                return $row["emne_id"];
            }
        }
    }
}


function getMessage($pin){
    $sql = "SELECT emne.emnekode, emne.emnenavn, emne.pinkode, 
         melding.svar, melding.question, melding.melding_id, melding.dato, melding.upassende_melding
         FROM emne INNER JOIN melding ON emne.emne_id = melding.emne_id WHERE pinkode='$pin'";
    $row = sqlQuery($sql);
    return $row;
}


function getComment($meldingId){
    $sql = "SELECT kommentar.kommentar_id, kommentar.melding_id, kommentar.kommentar FROM kommentar WHERE melding_id='$meldingId'";
    $row = sqlQuery($sql);
    return $row;
}

function showMessage($pin)
{
    $row = getMessage($pin);
    global $currentStudentId; 
    if ($row) {
        //Spørsmål and svar:
        foreach ($row as $row) {
            echo "<br><br>[". $row["dato"]. "]";

            // Show foreleser report count:
            if (isset($_SESSION["foreleser_id"])) {
                if (!empty($row["upassende_melding"])){
                        echo "<br>Melding rapportert ". $row["upassende_melding"]. " ganger.";
                    }
            }
            
            echo "<br> Spørsmål: " . $row["question"];
            if (!empty($row["svar"])) {
                echo "<br> Svar: " . $row["svar"];
            }

            //Kommentar:
            $comment = getComment($row["melding_id"]);
            if ($comment);
            foreach ($comment as $comment) {
                echo "<br> Kommentar: ";
                echo $comment["kommentar"];
            }

?>
            <!-- Send kommentar -->
            <form method="POST">
                <input type="hidden" name="studentID" value="<?php echo $currentStudentId; ?>">
                <input type="hidden" name='meldingID' value="<?php echo $row['melding_id'] ?>">
                <input type="hidden" name="pin" value="<?php echo $pin ?>">
                <label>Kommentar:</label>
                <br>
                <textarea name="kommenter" rows="4" cols="48" minlength="3" maxlength="26"></textarea>
                <button type="submit" name="button">Svar</button>
            <!-- </form> -->

            <!-- Rapporter melding -->
            <!-- <form method="POST"> -->
                <input type="hidden" name='report' value="<?php echo $row['melding_id'] ?>">
                <input type="hidden" name="pin" value="<?php echo $pin ?>">
                <button id="report" type="submit" name="rapporter">Report</button>
            </form>
<?php

        }
    }
}

function commentMessage($kommentar, $melding_id, $currentStudentId){
    if ($currentStudentId >= 1) {
        // IF students:
        if(strlen($kommentar) > 26){
            // "<p>Feil input lengde</p>"
            // Burde legges i en try catch som så registreres av graylog
        }
        else {
            $sql = "INSERT INTO kommentar (kommentar, melding_id, student_id) 
                VALUES ('$kommentar', '$melding_id', '$currentStudentId')";
            sqlQuery($sql);
        }
    } else {
        // If guest users:
        if(strlen($kommentar) > 26){
            // "<p>Feil input lengde</p>"
        }
        else {
            $sql = "INSERT INTO kommentar (kommentar, melding_id, student_id) 
                    VALUES ('$kommentar', '$melding_id', NULL)";
            sqlQuery($sql);
        }
    }
}

function reportMessage($id){
    if(!preg_match("/^[0-9]*$/", $id)){
        // Feil input
    }
    else{
        $sql = ("UPDATE melding SET upassende_melding = (upassende_melding + 1) WHERE melding_id = ('" . $id . "')");
        sqlQuery($sql);
    }
}
?>