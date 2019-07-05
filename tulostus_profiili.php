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
        <link media="print" href="style.css" />
        <style>
        body {
            font-family: 'Abel';font-size: 14px;
        }
        </style>
        
    </head>
    <body>
        
        <?php
            echo '<div style="width: 210mm;" align="left" class="reuna">';
            echo '<div class="kommentitpohja">';
            echo '<img src="/profiilikuvat/tuomasalanen.jpg" class="profiilikuva" width="40%" heigth="100%"></img>';
            echo '<div><h1 class="nimi">Tuomas Alanen</h1></div>';
            //abihaku
            $kayttajanimi = "a";
            $kommenttihaku = $con->prepare("SELECT * FROM kronikka_kommentit WHERE abi = ? ORDER BY id DESC");
            if( $kommenttihaku &&
                $kommenttihaku->bind_param("s", $kayttajanimi) &&
                $kommenttihaku->execute() &&
                $kommenttihakutulos = $kommenttihaku->get_result()
              ) {
                foreach ($kommenttihakutulos as $row) {
                    $kommentti = $row['kommentti'];
                    $kommentti = mysqli_real_escape_string($con,$kommentti);
                    echo '<div class="kommentti"><p class="kommentti"> > ' . $kommentti . "</p></div>";
                    
                }

            } else {
                echo "3Tapahtui odottamaton virhe, yritä uudelleen...";
            }
            $kommenttihaku->close();
            echo '</div></div>';
            echo '<div style="width: 5mm;" align="right" class="pystyreuna"></div>';
        
        ?>
    </body>
</html>
<?php
include "footer.php";
?>