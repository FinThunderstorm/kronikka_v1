<?php
include "config.php";

if(isset($_SESSION['kayttajanimi'])){
        header('Location: home.php');
}


if(isset($_POST['but_submit'])){

    $syotettykayttajanimi = mysqli_real_escape_string($con,$_POST['lomake_kayttajanimi']);
    $syotettysalasana = $_POST['lomake_salasana'];        
    $syotettysalasana = mysqli_real_escape_string($con,$syotettysalasana);
    
    
    $kirjautumishaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE kayttajanimi=?");
    if( $kirjautumishaku &&
        $kirjautumishaku->bind_param("s", $syotettykayttajanimi) &&
        $kirjautumishaku->execute() &&
        $kirjautumishakutulos = $kirjautumishaku->get_result()
        ) {
            foreach ($kirjautumishakutulos as $row) {
                $kantasalasana = $row['salasana'];
                $kantasalasana = mysqli_real_escape_string($con,$kantasalasana);
            }
    } else {
        echo "Tapahtui odottamaton virhe, yritä uudelleen...";
    }
    $kirjautumishaku->close();

    if(password_verify($syotettysalasana, $kantasalasana)){
        $_SESSION['kayttajanimi'] = $syotettykayttajanimi;
        header('Location: home.php');
    }else{
        echo "Käyttäjänimi tai salasana väärin";
    }   
}
?>
<html>
    <head>
        <title>KLA Kronikka 2019</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body class="kirjautumissivu">
        <div height=100%>
            
                <div class="kirjautumislaatikko">
                    <h1 style="font-size:30px;">Klassikan Kronikka 2019</h1>
                    <form method="post" action="">        
                        <input type="text" class="kirjautumisloota" id="kayttajanimi" name="lomake_kayttajanimi" placeholder="Käyttäjänimi" required/><br>
                            <input type="password" class="kirjautumisloota" id="salasana" name="lomake_salasana" placeholder="Salasana" required/><br>
                        <input type="submit" class="kirjautumisloota" value="Kirjaudu" name="but_submit" id="but_submit" /><br><br></form>
                        <form method="post" action="palautasalasana.php"><input type="submit" class="kirjautumisloota" value="Unohditko salasanasi?" name="but_resetoi" id="but_resetoi" /></form>
                </div>
            
        </div>
    </body>
</html>
<?php
include "footer.php";
?>