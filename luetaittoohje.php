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
        <style>
        body {
            font-family: 'Abel';font-size: 14px;
            margin: 0;
        }
        </style>
        
    </head>
    <body>
        
       
       
        
        
        <script type="text/javascript">
                function loadJSON(callback) {   

                    var xobj = new XMLHttpRequest();
                        xobj.overrideMimeType("application/json");
                    xobj.open('GET', 'taitto_ohje_2019.json', true); // Replace 'my_data' with the path to your file
                    xobj.onreadystatechange = function () {
                          if (xobj.readyState == 4 && xobj.status == "200") {
                            // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
                            callback(xobj.responseText);
                          }
                    };
                    xobj.send(null);  
                }
                
                function lue(t){
                    var ohjeet = [];
                    var i = 1;
                    var json = JSON.parse(t);
                    
                    while(i<json.length){
                        ohjeet = ohjeet.concat(json[i]);
                        i = i + 2;
                    }
                    console.log(ohjeet);
                }
            
                loadJSON(lue);
            
        </script>
    </body>
</html>


