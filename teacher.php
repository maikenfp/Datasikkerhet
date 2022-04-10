<?php
    session_start();
    require 'config/Database.php';

    $database = new Database();
    $db = $database->connect();
    $fid = $_SESSION['foreleser_id'];

    // TESTING
    var_dump($fid);
    return;

    if(empty($fid)){
        header('Location: index.php');
    } else {
    $sql = "SELECT emne_id from foreleser_emne WHERE foreleser_id='$fid'";
    $stmt = $db->query($sql);
    $result = $db->query($sql);
    $row_count = $result->fetchColumn();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $arr=array();
    if($row){
        foreach ($row as $row) {
            $n = $row['emne_id'];
            //$n = "$n"
             array_push($arr, "$n");
    }

}

    $in = '(' . implode(',', $arr) .')';
}
?>


<!DOCTYPE html>
<html lang="nb">
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="UTF-8" />
        <title>Foreleser</title>
        <!--force php to load css-->
        <link rel="stylesheet" href="./style.css?v=<?php echo time(); ?>">

    </head>
    <body>
        <main>
            <h1>Foreleser</h1>
            <form action="teachersubject.php" method="post" class="form">
                <label for="subject">Emne:<span class="required"></span></label>
                <select name="subject" required>
                    <option disabled selected value>Velg emne</option>

                    <?php
                    $database = new Database();
                    $db = $database->connect();

                    $query = "SELECT * FROM emne WHERE emne_id IN $in";
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
        </main>


        <a href="logout.php">Logout</a>
        <a href="bytt_passord/change_foreleser.php">Bytt passord</a>
    </body>
</html>
