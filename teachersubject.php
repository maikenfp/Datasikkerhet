<?php
session_start();
require 'config/Database.php';

$fid = $_SESSION['foreleser_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['emneID'] = strip_tags($_POST['subject']);
}
$emneID = $_SESSION['emneID'];


$database = new Database();
$db = $database->connect();

if (empty($fid)) {
    header('Location: index.php');
} else {
    $query = "SELECT * from emne WHERE emne_id=$emneID";
    $stmt = $db->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $emnenavn = $row['emnenavn'];
}
?>
<!doctype html>
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
        <h1>
            Emne:
            <?php
                echo $emnenavn;
            ?>
        </h1>
        <?php
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * from melding WHERE emne_id=$emneID AND svar IS NULL";
        $stmt = $db->query($sql);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($row) {

            foreach ($row as $row) {
                echo "<div class='respond-form'>";
                echo "<br>Dato:" . $row["dato"] . "<br>Spørsmål: " . $row["question"] . "<br>Svar: " . $row["svar"];
                $meldingID = $row['melding_id'];
        ?>
                <form action="response_send.php" method="post" class="teacher-form">
                    <textarea name="reply" rows="4" cols="28" required></textarea>
                    <input type="hidden" name="responseID" value="<?php echo $meldingID; ?>" />
                    <input type="hidden" name="emneID" value="$emneID" />
                    <button type="submit">Svar</button>
                </form>
                </div>
        <?php

            }
        }

        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * from melding WHERE emne_id=$emneID";
        $stmt = $db->query($sql);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Tidligere besvarte meldinger:</h2>";
        echo "<div>";
        if ($row) {
            foreach ($row as $row) {
                echo "<div class='respond-form'>";
                echo "<br>Dato:" . $row["dato"] . "<br>Spørsmål: " . $row["question"] . "<br>Svar: " . $row["svar"];
                $meldingID = $row['melding_id'];
            }
        }
        ?>
        </div>
        <a href="logout.php">Logout</a>
    </main>
</body>

</html>