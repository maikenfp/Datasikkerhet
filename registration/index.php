<!DOCTYPE html>
<html>
    <head>
    <title>Registering av brukere</title>
    <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <div class="header">
            <h2>Hva slags bruker vil du registere?</h2>
        </div>
        <form action="student.php" method="get">
            <div>
                <input type="submit" value="STUDENT">
            </div>
        </form>
        <form action="foreleser.php" method="get">
            <div>
                <input type="submit" value="FORELESER">
            </div>
        </form>
        <h1>HAR DU ALLEREDE EN BRUKER? LOGG INN HER:</h1>
        <a href="../index.php">LOGG INN</a>
    </body>
</html>
