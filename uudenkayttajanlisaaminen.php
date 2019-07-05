<?php
include "kirjautumistarkastus.php";
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
        <?php include "kayttajastatus.php"; ?>
        <form method="POST" action="lisaa_kayttaja.php">
            <select id="luokka" name="lomake_luokka" required>
                <option value="">Valitse luokka</option>
                <?php 
                    $luokkahaku = $con->prepare("SELECT * FROM kronikka_luokat WHERE vuosikerta = ?");
                    if( $luokkahaku &&
                        $luokkahaku->bind_param("i", $kayttajavuosikerta) &&
                        $luokkahaku->execute() &&
                        $luokkahakutulos = $luokkahaku->get_result()
                      ) {
                        foreach ($luokkahakutulos as $row) {
                            $luokkavalikkoon = $row['luokka'];
                            $luokkavalikkoon = mysqli_real_escape_string($con,$luokkavalikkoon);
                            echo "<option value=".$luokkavalikkoon.">" . $luokkavalikkoon . "</option>";
                        }

                    } else {
                        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
                    }
                    $luokkahaku->close();
                ?>
            </select><br>
            <input type="text" class="textbox" id="kayttajanimi" name="lomake_nimi" placeholder="Nimi" required/><br>
            <input type="text" class="textbox" id="salasana" name="lomake_salasana" placeholder="Salasana" required/><br>
            <?php
                if($kayttajaoikeusnumero >= $tarvittavaoikeusnumero){
                echo '<select name="lomake_oikeusryhma" id="oikeusryhma" required>';
                $oikeusryhmahaku = $con->prepare("SELECT * FROM kronikka_oikeusryhmat");
                if( $oikeusryhmahaku &&
                    $oikeusryhmahaku->execute() &&
                    $oikeusryhmahakutulos = $oikeusryhmahaku->get_result()
                  ) {
                    foreach ($oikeusryhmahakutulos as $row) {
                        $oikeusryhma = $row['oikeusryhma'];
                        /*$abi = mysqli_real_escape_string($con,$abi);*/
                        $oikeusnumero = $row['oikeusnumero'];
                        /*$abinimi = mysqli_real_escape_string($con,$abinimi);*/
                        echo "<option value=".$oikeusryhma.">" . $oikeusryhma . "</option>";
                    }

                } else {
                    echo "Tapahtui odottamaton virhe, yritä uudelleen...";
                }
                $oikeusryhmahaku->close();
                echo '</select><br> ';
                }
            ?>
            <input type="submit" value="Tallenna" name="but_submit" id="but_submit" />
        </form>
    </body>
</html>
<?php
include "footer.php";
?>