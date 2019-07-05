<?php
include "kirjautumistarkastus.php";
include "kommenttihaku.php";

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
        <p>Käyttäjän <?php echo $lisaajanimi;?> kommentin poisto</p>
        <br>
        <form method="POST" action="poistetunkommentintallennus.php">
            Luokka: <select name="luokka" disabled><option><?php echo $luokka;?></option></select><br>
            Abiturientti: <select name="abinimi" disabled><option><?php echo $abinimi;?></option></select><br>
            Lisääjä: <select name="lisaajanimi" disabled><option><?php echo $lisaajanimi;?></option></select><br>
            Poistettava kommentti: <textarea name="kommentti" disabled><?php echo $kommentti;?></textarea><br>
            Poiston syy: <textarea name="syy" required></textarea><br>
            <button type="submit" name="viestiid" value="<?php echo $viestiid;?>">Vahvista poisto</button>
        </form>
    </body>
</html>
<?php
include "footer.php";
?>

