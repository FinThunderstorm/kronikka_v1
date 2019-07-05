<?php
include "kirjautumistarkastus.php";
?>
<!doctype html>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
        <style>
        body {
            font-family: 'Abel';font-size: 14px;
        }
        </style>
    </head>
    <body>
        <h1>Klassikan Kronikka 2019</h1>
        <?php
            include "kayttajastatus.php";
        ?>
        <p>Kommenttien hallinta</p>
        <?php
            echo '<table>';
            //abihaku
            $kommenttihaku = $con->prepare("SELECT * FROM kronikka_kommentit WHERE vuosikerta = ? ORDER BY id DESC");
            if( $kommenttihaku &&
                $kommenttihaku->bind_param("s", $kayttajavuosikerta) &&
                $kommenttihaku->execute() &&
                $kommenttihakutulos = $kommenttihaku->get_result()
              ) {
                foreach ($kommenttihakutulos as $row) {
                    $kommentti = $row['kommentti'];
                    $kommentti = mysqli_real_escape_string($con,$kommentti);
                    echo "<tr>";
                    echo "<td>" . $kommentti . "</td>";
                    echo "</tr>";
                }

            } else {
                echo "3Tapahtui odottamaton virhe, yritä uudelleen...";
            }
            $kommenttihaku->close();
            echo '</table>';
        ?>
    </body>
</html>
<?php
include "footer.php";
?>