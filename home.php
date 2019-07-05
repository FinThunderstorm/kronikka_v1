<?php
include "kirjautumistarkastus.php";
?>
<!doctype html>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Klassikan Kronikka 2019</h1>
        <?php
            include "kayttajastatus.php";
        ?>
        <form action="/kommentointi.php">
         <button type="submit">Kommentointi</button>
        </form>
        <br>
        <form action="/kuvan_lisays.php">
         <button type="submit">Profiilikuvan lataaminen</button>
        </form>
        <br>
        <form method='post' action="">
            <input type="submit" value="Kirjaudu ulos" name="but_logout">
        </form>
    </body>
</html>
<?php
include "footer.php";
?>