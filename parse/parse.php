<?php
  require("MagicParser.php");

  function myRecordHandler($record)
  {

    $servername = "localhost";
    $user = "testparse";
    $pass = "0000";
    $dbname = "parsetest";


    try{
      //connexion à la bdd
      $dbco = new PDO("mysql:host=$servername;dbname=$dbname", $user, $pass);
      $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


      // Insertion des données dans la table
      $requete = 'INSERT INTO `catalogue` (`PRODUIT_POCLEUNIK`, `PRODUIT_REF`, `REFCIALE_ARCLEUNIK`, `REFCIALE_REFART`, `REFCIALE_REFCAT`, `POTRAD_DESI`, `REFCIALE_CTVA`
      , `FICTECH_MEMOCAT`, `FICTECH_MEMONET`, `PRODUIT_MARQUE`, `PRODUIT_CLEP01`, `PRODUIT_CLEP02`, `PRODUIT_CLEP03`, `PRODUIT_CLEP04`, `PRODUIT_CLEP06`, `PRODUIT_CLEP07`, 
      `PRODUIT_GCOLORIS`, `PRODUIT_GTAILLE`, `PRODUIT_CLEP12`, `REFCIALE_FICHEINA`, `REFCIALE_MODTE`, `PRODUIT_MODTE`, `ARTICLE_POIDS`, `ARTICLE_HNORMEL`, `ARTICLE_CATEG`)
      VALUES(
      "'.$record["PRODUIT_POCLEUNIK"].'","
      '.$record["PRODUIT_REF"].'","
      '.$record["REFCIALE_ARCLEUNIK"].'","
      '.$record["REFCIALE_REFART"].'","
      '.$record["REFCIALE_REFCAT"].'","
      '.$record["POTRAD_DESI"].'","
      '.$record["REFCIALE_CTVA"].'","
      '.$record["FICTECH_MEMOCAT"].'","
      '.$record["FICTECH_MEMONET"].'","
      '.$record["PRODUIT_MARQUE"].'","
      '.$record["PRODUIT_CLEP01"].'","
      '.$record["PRODUIT_CLEP02"].'","
      '.$record["PRODUIT_CLEP03"].'","
      '.$record["PRODUIT_CLEP04"].'","
      '.$record["PRODUIT_CLEP06"].'","
      '.$record["PRODUIT_CLEP07"].'","
      '.$record["PRODUIT_GCOLORIS"].'","
      '.$record["PRODUIT_GTAILLE"].'","
      '.$record["PRODUIT_CLEP12"].'","
      '.$record["REFCIALE_FICHEINA"].'","
      '.$record["REFCIALE_MODTE"].'","
      '.$record["PRODUIT_MODTE"].'","
      '.$record["ARTICLE_POIDS"].'","
      '.$record["ARTICLE_HNORMEL"].'","
      '.$record["ARTICLE_CATEG"].'")';
      
  
      $dbco->exec($requete);
  }
  
  catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
  }

  }
  
  MagicParser_parse("catalogue.XML","myRecordHandler","xml|HF_DOCUMENT/FLIGNE/");
  echo 'Les données du fichier xml ont été ajoutées dans la table';


?>