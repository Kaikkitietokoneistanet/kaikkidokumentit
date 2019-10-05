<?php

    $servername = "ip-osoite tai domain";
    $username = "käyttäjänimi";
    $password = "salasana";
    $dbname = "c1_documents";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Random stringin generointi funktio
    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    //Save data
    if ($_POST["datasave"] != "" || $_POST["nimisave"] != "") {
        $datasave = htmlentities($_POST["datasave"]);
        $nimisave = htmlentities($_POST["nimisave"]);
        $url = $_POST["url"];


        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "UPDATE dokumentit SET sisältö='$datasave', nimi='$nimisave' WHERE osoite='$url'";

        if ($conn->query($sql) === TRUE) {
            $message = "
            <div class='w3-panel w3-green'>
                <span onclick=\"this.parentElement.style.display='none'\"
class=\"w3-button w3-display-topright\">&times;</span>
                <h3>Tallennettu!</h3>
            </div> 
            ";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $conn->close();
    }



    //Create new document
    if ($_POST["new"] != "") {
        //Get post data
        $nimi = htmlentities($_POST["new"]);
        $email = htmlentities($_POST["email"]);
        $url = generateRandomString(20);
        
        $sql = "INSERT INTO dokumentit (nimi, email, osoite, sisältö)
        VALUES ('$nimi', '$email', '$url', 'Tervetuloa käyttämään avoimen lähdekoodin dokumentteja.')";


        if ($conn->query($sql) === TRUE) {

            $message = "
            <div class='w3-panel w3-green'>
                <h3>Tehty!</h3>
                <p>Voit muokata tekemääsi dokumenttia <a href='?edit=$url'>tässä</a> osoitteessa</p>
            </div>";

            $msg = "Olet tehnyt uuden dokumentin. Voit muokata sitä osoitteessa: https://kaikkitietokoneista.net/dokumentit?edit=$url"; //Muokkaa tähän oma osoite

            // use wordwrap() if lines are longer than 70 characters
            $msg = wordwrap($msg,70);

            // send email
            mail($email,"Uusi dokumentti!", $msg);
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        $conn->close();
    }
?>

<head>
    <link rel="stylesheet" href="w3.css">
    <script src="jquery-3.4.1.min.js"></script>
    <meta charset="utf-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumentit.cf</title>
    <style>
        .notes {
            background-attachment: local;
            background-image:
                linear-gradient(to right, white 10px, transparent 10px),
                linear-gradient(to left, white 10px, transparent 10px),
                repeating-linear-gradient(white, white 30px, #ccc 30px, #ccc 31px, white 31px);
            line-height: 31px;
            padding: 8px 10px;
        }
    </style>
</head>

<div id="modal" class="w3-modal">
    <div class="w3-modal-content">
      <div class="w3-container w3-padding-16">
        <form action="" method="POST">
            <span onclick="document.getElementById('modal').style.display='none'" class="w3-button w3-display-topright">&times;</span>
            <h1>Tee uusi dokumentti</h1>
            <input type="text" name="new" id="documentname" placeholder="Dokumentin nimi..." class="w3-input w3-border" autocomplete="off">
            <br>
            <input type="text" name="email" id="email" placeholder="Sähköpostisi..." class="w3-input w3-border">
            <br>
            <button type="submit" class="w3-button w3-black" id="luo">Luo</button>
        </form>
      </div>
    </div>
</div>

<header class="w3-blue">
    <button class="w3-button w3-circle w3-black" onclick="document.getElementById('modal').style.display='block'" title="Uusi dokumentti">+</button>
</header>
<?php
    if ($message != "") {
        echo $message;
    }
?>
<?php
    if ($_GET["edit"] != "") {
        $osoite = $_GET["edit"];
        $sql = "SELECT * FROM dokumentit
        WHERE osoite='$osoite';";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<div class='w3-row'><input type='text' id='nimi' class='w3-input w3-border' value='" . $row["nimi"]. "'>";
                echo "
                    <script>
                        function tallenna() {
                            var nimi = $( \"#nimi\" ).val();
                            var data = document.getElementById(\"data\").innerHTML;
                            $.post( \"\", { datasave: data, nimisave: nimi, url: '" . $osoite . "' } );
                            alert('Tallennettu');
                        }
                    </script>
                ";
                ?>

                    <div class="w3-border w3-third w3-container">
                        <button class="w3-button" onclick="document.execCommand('italic',false,null);" title="Kursivoitua teksitä"><i>I</i>
                        </button>
                        <button class="w3-button" onclick="document.execCommand( 'bold',false,null);" title="Tummennettua tekstiä"><b>B</b>
                        </button>
                        <button class="w3-button" onclick="document.execCommand( 'underline',false,null);"><u>U</u>
                        </button>
                    </div>

                    <button class="w3-button w3-third w3-cyan" onclick="tallenna()">Tallenna</button>

                    <!-- Avaa modaali -->
                    <button onclick="document.getElementById('jakomodaali').style.display='block'"
                    class="w3-button w3-black w3-right w3-third">Jaa</button>
                </div>
                <!-- The Modal -->
                <div id="jakomodaali" class="w3-modal">
                    <div class="w3-modal-content">
                        <div class="w3-container w3-padding-16">
                        <span onclick="document.getElementById('jakomodaali').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <p>Jaettava linkki:</p>
                        <script>
                            function kopioi() {
                                var copyText = document.getElementById("linkki");
                                copyText.select();
                                copyText.setSelectionRange(0, 99999);
                                document.execCommand("copy");
                                alert("Kopioitu!");
                            }
                        </script>
                        <input style="width: 100%;" type="text" readonly="no" value="<?php echo $actual_link; ?>" id="linkki"><br><br><button onclick='kopioi()' class="w3-button w3-black">Kopioi linkki</button>
                        </div>
                    </div>
                </div>
                <?php
                echo "<hr><div class='w3-padding-16'><p id='data' style='min-height: 40%; width: 100%;' class='notes w3-container' contenteditable='true'>" . $row["sisältö"]. "</p></div>";
            }
        } else {
            echo "Tiedostoa ei ole olemassa";
        }
    }
?>
<footer class="w3-blue">
    Tekijänä: <a href="https://kaikkitietokoneista.net">kaikkitietokoneista.net</a>
</footer>
