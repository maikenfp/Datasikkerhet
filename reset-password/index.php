<?php
    session_start();

    function validate($data) {
        $data = preg_replace('/[^A-Za-z0-9@. ]/i', '', $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
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
	            <p class="error"><?php echo validate(strip_tags($_GET['error'])); ?></p>
            <?php } ?>
            <form action="password-reset.php" method="post" class="form">
                <label for="epost">Epost:<span class="required"></span></label>
                    <input type="text" name="epost"/>
                    <button type="submit" name="submit">Go</button>
            </form>
        </main>
    </body>
</html>