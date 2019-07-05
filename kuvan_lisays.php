<?php
include "kirjautumistarkastus.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Klassikan Kronikka 2019</h1><br>
        <form action="lataa_kuva.php" method="post" enctype="multipart/form-data">
            Valitse kuvatiedosto:
            <input type="file" name="kuvaladattavaksi" id="kuvaladattavaksi" required>
            <input type="submit" value="Lataa palvelimelle" name="submit">
        </form>
        <br>
        <form action="/home.php">
             <button type="submit">Palaa alkuun</button>
        </form>
    </body>
</html>
<?php
include "footer.php";
?>