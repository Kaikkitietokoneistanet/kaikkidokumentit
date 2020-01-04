<?php
  session_start();

  $dbpalvelimenosoite = "ip-osoite tai hostname";
  $dbkayttajanimi = "kayttajanimi";
  $dbsalasana = "salis";
  $dbnimi = "databasen_nimi";

  /*
  CREATE TABLE dokumentit (

  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  kayttajanimi VARCHAR(100) NOT NULL,

  osoite VARCHAR(50) NOT NULL,

  sisältö VARCHAR(100000) NOT NULL,

  reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

  )

  */
 ?>
<!DOCTYPE html>
<html lang="fi" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Dokumentit | kaikkitietokoneista.net</title>
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
     <script src="//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
     <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
     <link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
     <script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
  </head>
  <body>
    <?php include 'ylaosa.php'; ?>
    <?php
    if (isset($_SESSION["kirjautunut"])) {
      if ($_POST["sisalto"] != "") {
        $yhdistys = new mysqli($dbpalvelimenosoite, $dbkayttajanimi, $dbsalasana, $dbnimi);

        $osoite = $_POST["osoite"];
        $osoiteuusi = $_POST["osoiteuusi"];
        $tallennettava = $_POST["sisalto"];
        $kayttajanimi = $_SESSION["kirjautunut"];

        $sql = "UPDATE dokumentit SET sisältö='$tallennettava', osoite='$osoiteuusi' WHERE osoite='$osoite' AND kayttajanimi='$kayttajanimi'";

        if ($yhdistys->query($sql) === TRUE) {
          ?>
          <div class="container">
            <div class="alert alert-success alert-dismissible" role="alert">
              <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
              <strong>Tallennettu</strong> kohteeseen <?php echo $osoiteuusi; ?>.
            </div>
          </div>
          <?php
        }
        $yhdistys->close();
      }

      if ($_GET["nimi"] != "") {
        $nimi = $_GET["nimi"];

        $yhdistys = new mysqli($dbpalvelimenosoite, $dbkayttajanimi, $dbsalasana, $dbnimi);

        if ($yhdistys->connect_error) {
          mail($sysadmin, "Sivustolla ongelma MySQL:n kanssa", $conn->connect_error);
        }

        $kayttajanimi = $_SESSION["kirjautunut"];
        $sql = "SELECT osoite, sisältö FROM dokumentit WHERE osoite='$nimi'";
        $vastaus = $yhdistys->query($sql);

        if ($vastaus->num_rows > 0) {
            // output data of each row
            echo "<div class='container'>";
            while($rivi = $vastaus->fetch_assoc()) {
              ?>
              <script>
              function download(filename, text) {
                var element = document.createElement('a');
                element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
                element.setAttribute('download', filename);

                element.style.display = 'none';
                document.body.appendChild(element);

                element.click();

                document.body.removeChild(element);
              }
              </script>
              <div class="container">
                <form method="POST" action="" id="lahetyslomake">
                  <div class="form-group">
                    <input type="text" value="<?php echo $rivi["osoite"]; ?>" name="osoiteuusi">
                  </div>
                  <div class="hiddenit"></div>
                  <input type='hidden' name='osoite' value='<?php echo $rivi["osoite"] ?>'>
                  <input type='submit' class="btn btn-primary" value='Tallenna'>
                  <a id="lataa" class="btn btn-success">Lataa <?php echo $rivi["osoite"]; ?>.html</a>
                </form>
                <script>
                  $("#lataa").click(function() {
                    download('<?php echo $rivi["osoite"]; ?>.html', '<meta charset="utf-8" /> ' + $('#summernote').summernote('code'));
                  });
                </script>
                <hr>
                <div id="summernote"><?php echo $rivi["sisältö"]; ?></div>
              <?php
            }
          }
        ?>
        </div>
        <script>
        $(document).ready(function() {
          $('#summernote').summernote();
        });

        function tallennahiddeninputtiin() {
          var hvalue = $('#summernote').summernote('code');
          $(".hiddenit").html("<input type='hidden' name='sisalto' value=' " + hvalue + " '/>");
        }

        setInterval(tallennahiddeninputtiin, 300);
        </script>
        <?php
      } else {
        if ($_GET["uusinimi"] != "") {
          $yhdistys = new mysqli($dbpalvelimenosoite, $dbkayttajanimi, $dbsalasana, $dbnimi);

          if ($yhdistys->connect_error) {
            mail($sysadmin, "Sivustolla ongelma MySQL:n kanssa", $conn->connect_error);
          }

          $stmt = $yhdistys->prepare("INSERT INTO dokumentit (kayttajanimi, osoite, sisältö) VALUES (?, ?, ?)");
          $stmt->bind_param("sss", $kayttajanimi, $nimi, $sisältö);

          $kayttajanimi = $_SESSION["kirjautunut"];
          $nimi = $_GET["uusinimi"];
          $sisältö = "<h1>Tiedosto nimeltä $nimi</h1><p>on muokattava.</p>";
          $stmt->execute();

          ?>
          <script>
          location.search = "";
          </script>
          <?php

          $stmt->close();
          $yhdistys->close();
        }

        if ($_GET["poista"] != "") {
          $yhdistys = new mysqli($dbpalvelimenosoite, $dbkayttajanimi, $dbsalasana, $dbnimi);

          if ($yhdistys->connect_error) {
            mail($sysadmin, "Sivustolla ongelma MySQL:n kanssa", $conn->connect_error);
          }

          $stmt = $yhdistys->prepare("DELETE FROM dokumentit WHERE osoite=? AND kayttajanimi=?;");
          $stmt->bind_param("ss", $nimi, $kayttajanimi);

          $kayttajanimi = $_SESSION["kirjautunut"];
          $nimi = $_GET["poista"];
          $stmt->execute();

          ?>
          <script>
          location.search = "";
          </script>
          <?php

          $stmt->close();
          $yhdistys->close();
        }

        $yhdistys = new mysqli($dbpalvelimenosoite, $dbkayttajanimi, $dbsalasana, $dbnimi);

        if ($yhdistys->connect_error) {
          mail($sysadmin, "Sivustolla ongelma MySQL:n kanssa", $conn->connect_error);
        }

        $kayttajanimi = $_SESSION["kirjautunut"];
        $sql = "SELECT osoite FROM dokumentit WHERE kayttajanimi='$kayttajanimi'";
        $vastaus = $yhdistys->query($sql);

        if ($vastaus->num_rows > 0) {
            // output data of each row
            echo "<div class='container'>";
            while($rivi = $vastaus->fetch_assoc()) {
              ?>
              <div class="col-md-4">
                <h4><?php echo $rivi["osoite"]; ?></h4>
                <a href='?poista=<?php echo $rivi["osoite"]; ?>'><span class='glyphicon glyphicon-trash'></span></a><a href='?nimi=<?php echo $rivi["osoite"]; ?>'><span class='glyphicon glyphicon-edit'></span></a>
              </div>
              <?php
            }
            echo "</div>";
            ?>

            <a data-toggle="modal" href="#luouusi" class="btn btn-success btn-sm" style="position: absolute; bottom: 20px; right: 20px;">
              <span class="glyphicon glyphicon-plus"></span>
            </a>
            <!-- Modal -->
              <div class="modal fade" id="luouusi" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Uusi tiedosto</h4>
                    </div>
                    <div class="modal-body">
                      <form action="">
                        <div class="form-group">
                          <label for="uusinimi">Nimi:</label>
                          <input type="text" class="form-control" id="uusinimi" name="uusinimi">
                        </div>
                        <button type="submit" class="btn btn-success">Luo</button>
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                    </div>
                  </div>

                </div>
              </div>

            <?php
        } else {
          ?>
          <div class="container">
            <center><h1><div class="text-muted small">Täällä ei ole mitään... Luo tiedosto painamalla + merkkiä oikeassa alakulmassa.</h1></center>
          </div>

          <a data-toggle="modal" href="#luouusi" class="btn btn-success btn-sm" style="position: absolute; bottom: 20px; right: 20px;">
            <span class="glyphicon glyphicon-plus"></span>
          </a>
          <!-- Modal -->
            <div class="modal fade" id="luouusi" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Uusi tiedosto</h4>
                  </div>
                  <div class="modal-body">
                    <form action="">
                      <div class="form-group">
                        <label for="uusinimi">Nimi:</label>
                        <input type="text" class="form-control" id="uusinimi" name="uusinimi">
                      </div>
                      <button type="submit" class="btn btn-success">Luo</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                  </div>
                </div>

              </div>
            </div>

          <?php
        }
      }
    }
    ?>
  </body>
</html>
