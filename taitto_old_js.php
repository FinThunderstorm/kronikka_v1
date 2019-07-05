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
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="results.json"></script>
        <script src="html2canvas.min.js"></script>
        <style>
        body {
            font-family: 'Abel';font-size: 14px;
            margin: 0;
        }
        </style>
        
    </head>
    <body>
        <script type="text/javascript">
            //muuttujat
            var sivuja, sivunkorkeus, menossaSivulla, menossaOlevanSivunTayttoAste;
            sivuja = 1;
            menossaSivulla = 0;
            luoUusiSivu();
            console.log(menossaSivulla);
            sivunkorkeus = $("#1").height();
            console.log("Sivun korkeus:"+sivunkorkeus);
            
            //paakirjoitus
            luoUusiSivu();
            
            //ensimmainen sisaltosivu
            luoUusiSivu();
            
            getSisaltoAlue(1).innerHTML = '<p class="kommentti" id="kommentti_abi_1">Kansilehti</p>';
            getSisaltoAlue(2).innerHTML = '<p class="kommentti">Pääkirjoitus</p>';
            console.log("Kommentin korkeus:"+$("#kommentti_abi_1").height());
            
            
            function luoAbinKehys(abi){
                var aiempisivu = menossaSivulla-1;
                var tarkastuskehysid = abi+aiempisivu;
                console.log("Tarkastetaan kehystä: " + tarkastuskehysid);
                var tarkastuskehys =  document.getElementById(tarkastuskehysid);
                if (typeof(tarkastuskehys) != 'undefined' && tarkastuskehys != null)
                {
                    var kehys = document.createElement("DIV");
                    kehys.id = abi+menossaSivulla;
                    kehys.className = "kehys-2";
                    getSisaltoAlue(menossaSivulla).appendChild(kehys);
                    return;
                } else{
                    var kehys = document.createElement("DIV");
                    kehys.id = abi+menossaSivulla;
                    kehys.className = "kehys";
                    getSisaltoAlue(menossaSivulla).appendChild(kehys);
                    return;
                }
            }
            
            function lisaaKommenttiSivulle(abi,kommentti, numero){
                if(getAbiKehys(abi+menossaSivulla) == null){
                    luoAbinKehys(abi);
                }
                var kommenttiDivi = document.createElement("DIV");
                var kommenttiID = "kommentti_"+abi+"_"+numero;
                var kommenttiP = document.createElement("p");
                var kommenttiS = document.createTextNode(kommentti);
                kommenttiP.id = kommenttiID;
                kommenttiP.className = "kommentti";
                kommenttiP.appendChild(kommenttiS);
                kommenttiDivi.appendChild(kommenttiP);
                getAbiKehys(abi+menossaSivulla).appendChild(kommenttiDivi);
                document.getElementById(kommenttiID).style.visibility = "hidden";
                var laatu = "kommentti";
                if(mahtuukoSivulle(laatu,abi,numero)){
                    document.getElementById(kommenttiID).style.visibility = "visible";
                    return;
                } else{
                    $('#'+kommenttiID).remove();
                    console.log("Sivu täynnä kommentin "+numero+" kohdalla.");
                    luoUusiSivu();
                    lisaaKommenttiSivulle(abi, kommentti, numero);
                    return;
                }
            }
            
            function lisaaNimiSivulle(abi, nimi, numero){
                if(sivunkorkeus-getSivullaTavaraa(menossaSivulla)<150){
                    luoUusiSivu();
                }
                if(getAbiKehys(abi+menossaSivulla) == null){
                    luoAbinKehys(abi);
                }
                var nimiID = "nimi_"+abi+"_"+numero;
                var nimiH = document.createElement("h1");
                var nimiS = document.createTextNode(nimi);
                nimiH.id = nimiID;
                nimiH.className = "nimi";
                nimiH.appendChild(nimiS);
                getAbiKehys(abi+menossaSivulla).appendChild(nimiH);
                document.getElementById(nimiID).style.visibility = "hidden";
                var laatu = "nimi";
                if(mahtuukoSivulle(laatu,abi,numero)){
                    document.getElementById(nimiID).style.visibility = "visible";
                    return;
                } 
                else{
                    $('#'+nimiID).remove();
                    console.log("Sivu täynnä nimen "+abi+" kohdalla.");
                    $('#'+abi+menossaSivulla).remove();
                    luoUusiSivu();
                    lisaaNimiSivulle(abi, nimi);
                    
                    return;
                }
            }
            
            async function lisaaKuvaSivulle(abi, kuvansijainti, numero){
                if(getAbiKehys(abi+menossaSivulla) == null){
                    luoAbinKehys(abi);
                }
                var kuvaID = "profiilikuva_"+abi+"_"+numero;
                /*var kuvaTagi = document.createElement("IMG");
                kuvaTagi.src = kuvansijainti;
                kuvaTagi.id = kuvaID;
                kuvaTagi.className = "profiilikuva";*/
                var mahtuukoSivulleKuva = false;
                var kuvaTagi = new Image();
                kuvaTagi.onload = function() {
                    if(this.height+getSivullaTavaraa(menossaSivulla)<sivunkorkeus){
                        mahtuukoSivulleKuva = true;
                    }
                }
                kuvaTagi.src = kuvansijainti;
                kuvaTagi.id = kuvaID;
                kuvaTagi.className = "profiilikuva";
                getAbiKehys(abi+menossaSivulla).appendChild(kuvaTagi);
                document.getElementById(kuvaID).style.visibility = "hidden";
                var laatu = "profiilikuva"; 
                console.log("mahtuuko "+abi+" kuva: "+mahtuukoSivulle(laatu,abi,numero)); 
                if(mahtuukoSivulle(laatu,abi,numero)){
                    document.getElementById(kuvaID).style.visibility = "visible";
                    return;
                } else{
                    $('#'+kuvaID).remove();
                    console.log("Sivu täynnä kuvan "+numero+" kohdalla.");
                    luoUusiSivu();
                    lisaaKuvaSivulle(abi, kuvansijainti, numero);
                    return;
                }
            }
            
            
            
            function mahtuukoSivulle(laatu, abi, numero){

                if(getElementinKorkeus(laatu,abi,numero) + getSivullaTavaraa(menossaSivulla) < sivunkorkeus){
                    return true;
                } else{
                    return false;
                }
            }
            
            function luoUusiSivu(){
                var uusiSivu = document.createElement("DIV");
                menossaSivulla = menossaSivulla + 1;
                uusiSivu.id = menossaSivulla;
                uusiSivu.className = "sivu";
                document.body.appendChild(uusiSivu);
                var uusiSisaltoAlue = document.createElement("DIV");
                var idNimi = "sisalto" + menossaSivulla;
                uusiSisaltoAlue.id = idNimi;
                uusiSisaltoAlue.className = "sisaltoalue";
                document.getElementById(menossaSivulla).appendChild(uusiSisaltoAlue);
                return;
            }
            
            function getAbiKehys(abi){
                return document.getElementById(abi);
            }
            
            function getSisaltoAlue(sivulta){
                var sisaltoalueenSivu = "sisalto" + sivulta;
                return document.getElementById(sisaltoalueenSivu);
            }
            
            function getSivullaTavaraa(sivu){
                var sivuntunnus = "#sisalto"+sivu;
                return $(sivuntunnus).height();
            }
            
            function getElementinKorkeus(laatu,abi,numero){
                var hakutunnus = "#"+laatu+"_"+abi+"_"+numero;
                return $(hakutunnus).height();
            }
            console.log("Sivulla 1: "+getSivullaTavaraa(1));
            console.log("Kommentin korkeus: "+getElementinKorkeus("kommentti","abi",1));
            console.log($("#1").height())
            console.log("Mahtuuko sivulle: "+mahtuukoSivulle("kommentti","abi",1))
            
            async function kasaaVuosikurssi(vuosikurssi){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                    var vuosikurssi = JSON.parse(this.responseText);
                    var i = 1;
                    while(i<vuosikurssi.length){
                        //0 = vuosikurssi
                        //1=luokka
                        //3=ro
                        //5=abi
                        //7=nimi
                        //9=profiilikuvan sijainti
                        //11=kommentit array
                        //7=abi
                        //8=nimi
                        //9=profiilikuvan sijainti
                        //10=kommentit array
                        if(vuosikurssi[i] == "luokka"){
                            i = i + 1;
                            //lisaaLuokkaSivulle(vuosikurssi[i],vuosikurssi[i+1]);
                            console.log(vuosikurssi[i] +" "+vuosikurssi[i+2]);
                            i = i + 3;
                        }
                        if(vuosikurssi[i] == "kayttajanimi"){
                            i = i + 1;
                            var abi = vuosikurssi[i];
                            i = i + 1;
                        }
                        if(vuosikurssi[i] == "abinimi"){
                            i = i + 1;
                            lisaaNimiSivulle(abi,vuosikurssi[i],i);
                            i = i + 1;
                        }
                        if(vuosikurssi[i] == "kuvasijainti"){
                            i = i + 1;
                            console.log("lisataan abin "+abi+" kuvaa");
                            if(vuosikurssi[i] == "wilma" || vuosikurssi[i] == "eikuvaa"){
                                
                            } else{
                                lisaaKuvaSivulle(abi,vuosikurssi[i],i);
                            }
                            i = i + 1;
                        }
                        sleep(500);
                        if(vuosikurssi[i] == "kommentti"){
                            i = i + 1;
                            lisaaKommenttiSivulle(abi,vuosikurssi[i],i);
                            i = i + 1;
                        }
                    }
                  }
                };
                xmlhttp.open("GET", "2019.json", true);
                xmlhttp.send();  
            }
            
           
            
        
            function sleep(milliseconds) {
              var start = new Date().getTime();
              for (var i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > milliseconds){
                  break;
                }
              }
            }
            

            
           
           
            
            kasaaVuosikurssi(2019);
            html2canvas(document.getElementById(5)).then(function(canvas){
                var base64image = canvas.toDataURL("image/png");
                window.open(base64image, "_blank");
            });
        </script>
        <!--<script type="text/javascript">
                var i = 0;
                lisaaNimiSivulle("abi","Abiturientti Testilainen",1);
                lisaaKuvaSivulle("abi","/profiilikuvat/l.jpg",1);
                while(i<150){
                    lisaaKommenttiSivulle("abi","kommentin lisailytesti"+i,i);
                    i = i + 1;
                }
                
              
                
        </script>-->
        <?php
        /*    
            $vuosikerta = 2019;
            $luokkahaku = $con->prepare("SELECT * FROM kronikka_luokat WHERE vuosikerta = ?");
            if( $luokkahaku &&
                $luokkahaku->bind_param("i", $vuosikerta) &&
                $luokkahaku->execute() &&
                $luokkahakutulos = $luokkahaku->get_result()
              ) {
                foreach ($luokkahakutulos as $row) {
                    $luokka = $row['luokka'];
                    $luokka = mysqli_real_escape_string($con,$luokka);
                    $ryhmanohjaaja = $row['ro'];
                    $ryhmanohjaaja = mysqli_real_escape_string($con,$ryhmanohjaaja);
                    
                    $kayttajahaku = $con->prepare("SELECT * FROM kronikka_kayttajat WHERE luokka = ?");
                    if( $kayttajahaku &&
                        $kayttajahaku->bind_param("s", $luokka) &&
                        $kayttajahaku->execute() &&
                        $kayttajahakutulos = $kayttajahaku->get_result()
                      ) {
                        $i = 1;
                        foreach ($kayttajahakutulos as $row) {
                            $kayttajanimi = $row['kayttajanimi'];
                            $kayttajanimi = mysqli_real_escape_string($con,$kayttajanimi);
                            include "tulostaprofiilikayttajanimella.php";
                            echo '<script type="text/javascript">';
                            echo 'lisaaNimiSivulle("'.$kayttajanimi.'","'.$abinimi.'",'.$i.');';
                            echo 'lisaaKuvaSivulle("'.$kayttajanimi.'","'.$kuvasijainti.'",'.$i.');';
                            
                            
                            
                            $i = $i+1;
                        }
                    } else {
                        echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
                    }
                    $kayttajahaku->close();
                }
            } else {
                echo "1Tapahtui odottamaton virhe, yritä uudelleen...";
            }
            $luokkahaku->close();
            */
        ?>
        
        
        
        
        
        
    </body>
</html>


