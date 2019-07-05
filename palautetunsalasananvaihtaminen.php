<?php
$kayttajanimi = $_GET['kt'];
$kayttajanimi = mysqli_real_escape_string($con,$kayttajanimi);
$hash = $_GET['h'];
$hash = mysqli_real_escape_string($con,$hash);
if(password_verify($kayttajanimi, $hash)==FALSE) {
    header('Location: index.php');
}
?>
<!DOCTYPE HTML html>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Klassikan Kronikka 2019</h1><br>
        <form method="POST" action="resetoisalasana.php"> 
            Aseta uusi salasanasi:
            <input type="password" class="textbox" id="salasana" name="lomake_salasana" placeholder="Salasana" required/><br>
            Vahvista uusi salasanasi:
            <input type="password" class="textbox" id="vahvistasalasana" name="lomake_vahvistasalasana" placeholder="Vahvista salasana" oninput="check(this)" required/><br>
            <script language='javascript' type='text/javascript'>
                function check(input) {
                    if (input.value != document.getElementById('salasana').value) {
                        input.setCustomValidity('Salasanojen tulee vastata toisiaan.');
                    } else {
                        // input is valid -- reset the error message
                        input.setCustomValidity('');
                    }
                }
             </script>
            <button type="submit" value="<?php echo $kayttajanimi?>" name="ss_submit" id="but_submit">Tallenna</button>
        </form>
    </body>
</html>
<?php
include "footer.php";
?>