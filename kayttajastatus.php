<?php
            echo "Kirjautunut: ".$kayttajanimi."<br />";
            echo "Käyttäjäryhmä: ".$kayttajaoikeusryhma."<br />";
            echo "Käyttäjäoikeusnumero: ".$kayttajaoikeusnumero."<br /><br />";
            echo    '<form action="/profiili.php">
                    <button type="submit">Oma profiili</button>
                    </form>'." ";

            if($kayttajaoikeusnumero >= '2'){
                echo    '<form action="/hallinta.php">
                        <button type="submit">Kommenttien hallinta</button>
                        </form>'." ";
            }
            
            if($kayttajaoikeusnumero >= '3'){
                echo    '<form action="/asetukset.php">
                        <button type="submit">Asetukset</button>
                        </form>'." ";
            }

            echo    '<form action="/home.php">
                    <button type="submit">Alkuun</button>
                    </form> <br />';
?>