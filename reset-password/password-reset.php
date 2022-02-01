<?php
    session_start();
    require '../config/Database.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $epost = $_POST['epost'];
         $_SESSION['epost'] = $epost;
    }
    
    $epost = $_SESSION['epost'];

    $database = new Database();
    $db = $database->connect();

    $query = "SELECT * from foreleser WHERE epost='$epost'";
    $stmt = $db->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(($epost === $row['epost'])){
        $_SESSION['epost'] = $epost;

    }else{
        header("Location: index.php?error=Email ikke registrert!");
    }

?>

<!DOCTYPE html>
<html lang="nb">
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="UTF-8" />
        <title>Reset Passord</title>
        <!--force php to load css-->
        <link rel="stylesheet" href="./style.css?v=<?php echo time(); ?>">

    </head>
    <body> 
        <main>
            <h1>Foreleser</h1>
            <?php if (isset($_GET['error'])) { ?>
	            <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <form action="new-password.php" method="post" class="form">
                    <label for="svar1"><?php
                        echo $row['sp1'];
                    ?></label>
                    <input type="text" name="svar1"/>
                    <label for="svar2"><?php
                        echo $row['sp2'];
                    ?></label>
                    <input type="text" name="svar2"/>
                    <button type="submit" name="submit">Go</button>
            </form>
        </main>
    </body>
</html>

