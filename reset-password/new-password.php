<?php
    session_start();
    require '../config/Database.php';


    function validate($data) {
        $data = preg_replace('/[^A-Za-z0-9@. ]/i', '', $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sv1 = validate(strip_tags($_POST['svar1']));
        $sv2 = validate(strip_tags($_POST['svar2']));
        $_SESSION['sv1'] = $sv1;
        $_SESSION['sv2'] = $sv2;
    }

    $sv1 = $_SESSION['sv1'];
    $sv2 = $_SESSION['sv2'];

    $epost = $_SESSION['epost'];


    

    $database = new Database();
    $db = $database->connect();

    $query = "SELECT * from foreleser WHERE epost='$epost'";
    $stmt = $db->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row['glemt_svar_1'] === $sv1 AND $row['glemt_svar_2'] === $sv2){
        
    } else {
        header("Location: password-reset.php?error=Feil svar!");
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
            <form action="new-password-post.php" method="post" class="form">
                    <label for="pw1">Nytt passord</label>
                    <input type="password" name="pw1"/>
                    <label for="pw2">Gjenta Passord</label>
                    <input type="password" name="pw2"/>
                    <button type="submit" name="submit">Go</button>
            </form>
        </main>
    </body>
</html>