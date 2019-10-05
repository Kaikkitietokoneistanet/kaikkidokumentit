# Selain-pohjainen dokumenttien hallinta

Tekijänä: [kaikkitietokoneista.net](https://kaikkitietokoneista.net)

## Asennus

Luo tietokanta (mysql) ja lisää sen tiedot kohtiin $servername, $username, $password ja $dbname alla olevan esimerkin mukaan.

    <?php
    	$servername = "ip-osoite tai domain";
        
        $username = "käyttäjänimi";
        
        $password = "salasana";
        
        $dbname = "c1_documents";

Seuraavaksi aja seuraava komento phpmyadminissa tai vastaavassa:

    CREATE TABLE dokumentit (
    
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    nimi VARCHAR(30) NOT NULL,
    
    osoite VARCHAR(30) NOT NULL,
    
    email VARCHAR(50) NOT NULL,
    
    sisältö VARCHAR(10000) NOT NULL,
    
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    
    )

Viimeisenä vaihda index.php:n riville 71 viestiksi:

    $msg = "Olet tehnyt uuden dokumentin. Voit muokata sitä osoitteessa: asennuksen_url_osoite?edit=$url"; //Muokkaa tähän oma osoite

## To do

 1. Estä sql-injektiot
 2. Lisää kommentit koodiin
 3. Tee oma CSS ja JS -kirjasto ohjelmalle
 4. Tee kustomoitavat asetukset
 5. Tee asennus-ohjelma

