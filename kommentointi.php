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
        <form method="POST" action="lisaa_kommentti.php">
            <!--TEE DROPDOWN & TIETOKANNASTA HAKU-->
            Luokka: <select name="luokka" onchange="naytaAbivalikko(this.value)" required>
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
            <div id="txtHint">Abiturientti: <select name="abi" required>
                <option value="">Valitse ensin luokka</option>
                </select>
            </div>
            <script>
                function naytaAbivalikko(str) {
                  var xhttp;  
                  if (str == "") {
                    document.getElementById("txtHint").innerHTML = "Abiturientti: <select name='abi' required><option value=''>Valitse ensin luokka</option></select>";
                    return;
                  }
                  xhttp = new XMLHttpRequest();
                  xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                      document.getElementById("txtHint").innerHTML = this.responseText;
                    }
                  };
                  xhttp.open("GET", "abivalikko.php?q="+str, true);
                  xhttp.send();
                }
            </script>
            Kommentti : <textarea name="kommentti" required></textarea><br />
            <input type="submit" value="Lisää" />
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

















