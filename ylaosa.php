<header>
  <nav class="navbar navbar-inverse" style="border-radius:0px;">
   <div class="container-fluid">
     <div class="navbar-header">
       <a class="navbar-brand" href="/">Kaikkitietokoneista</a>
     </div>
     <ul class="nav navbar-nav">
       <?php
       if (isset($_SESSION["kirjautunut"])) {

        ?>
       <li><a href="https://hallinta.kaikkitietokoneista.net:8080/webmail">Webmail</a></li>
       <li><a href="https://hallinta.kaikkitietokoneista.net:8080">Hallintapaneeli</a></li>
       <li><a href="/dokumentit.php">Dokumentit</a></li>
       <?php
       }
        ?>
     </ul>
     <ul class="nav navbar-nav navbar-right">
       <?php
       if (isset($_SESSION["kirjautunut"])) {
       ?>
       <li><a href="index.php?kirjaudu=ulos"><span class="glyphicon glyphicon-user"></span>Kirjaudu ulos</a></a></li>
       <?php
     } else {
        ?>
       <li><a href="#" data-toggle="modal" data-target="#rekisteroidymodaali"><span class="glyphicon glyphicon-user"></span> Rekisteröidy</a></li>
       <li><a href="#" data-toggle="modal" data-target="#kirjaudumodaali"><span class="glyphicon glyphicon-log-in"></span> Kirjaudu</a></li>
       <?php
       }
        ?>
     </ul>
     <div class="modal fade" id="kirjaudumodaali" role="dialog">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title">Kirjaudu</h4>
           </div>
           <div class="modal-body">
             <form method="post" action="index.php">
               <div class="form-group">
                 <label for="kayttajanimi">Käyttäjänimi:</label>
                 <input type="text" class="form-control" id="kayttajanimi" name="kayttajanimi">
               </div>
               <div class="form-group">
                 <label for="Salasana">Salasana:</label>
                 <input type="password" class="form-control" id="Salasana" name="salasana">
               </div>
               <div class="form-group">
                 <img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" />
                 <input type="text" name="captcha_code" size="10" maxlength="6" />
                 <a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">[ Uusi kuva ]</a>
               </div>
               <button type="submit" class="btn btn-success">Kirjaudu</button>
             </form>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">sulje</button>
           </div>
         </div>
       </div>
     </div>

     <div class="modal fade" id="rekisteroidymodaali" role="dialog">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title">Rekisteröidy</h4>
           </div>
           <div class="modal-body">
             <form method="post" action="index.php">
               <div class="form-group">
                 <label for="rkayttajanimi">Uusi käyttäjänimi:</label>
                 <input type="text" class="form-control" id="rkayttajanimi" name="rkayttajanimi">
               </div>
               <div class="form-group">
                 <label for="rsalasana">Uusi salasana:</label>
                 <input type="password" class="form-control" id="rsalasana" name="rsalasana">
               </div>
               <div class="form-group">
                 <img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" />
                 <input type="text" name="captcha_code" size="10" maxlength="6" />
                 <a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">[ Uusi kuva ]</a>
               </div>
               <button type="submit" class="btn btn-success">Rekisteröidy</button>
             </form>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">sulje</button>
           </div>
         </div>
       </div>
   </div>
 </nav>
</header>
