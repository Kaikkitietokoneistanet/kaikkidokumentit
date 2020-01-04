# Selainpohjainen dokumenttien hallinta

Tekijänä: [kaikkitietokoneista.net](https://kaikkitietokoneista.net) ja [roy.takanen.eu](https://roy.takanen.eu)

## Asennus

Luo tietokanta (mysql) ja lisää sen tiedot kohtiin $dbpalvelimenosoite, $dbkayttajanimi, dbsalasanapassword ja $dbnimi alla olevan esimerkin mukaan.
```php
    <?php
      session_start();

      $dbpalvelimenosoite = "ip-osoite tai hostname";
      $dbkayttajanimi = "kayttajanimi";
      $dbsalasana = "salis";
      $dbnimi = "databasen_nimi";

```

Seuraavaksi aja seuraava komento phpmyadminissa tai vastaavassa:

```mysql
    CREATE TABLE dokumentit (

    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    kayttajanimi VARCHAR(100) NOT NULL,

    osoite VARCHAR(50) NOT NULL,

    sisältö VARCHAR(100000) NOT NULL,

    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

    )

```

## To do

 1. Tarkasta, etttä sql-injektiot on estetty
 3. Lataa Bootstrap ja jQuery ohjelmaan lookaliksi
 4. Tee admin-osio
 5. Tee asennusohjelma
 6. Lisää tekstitiedostona lataus
