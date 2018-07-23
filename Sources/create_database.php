<?php

require_once('config.php');

global $db_host, $db_user, $db_password, $db_database;

$mysqli = new mysqli($db_host, $db_user, $db_password);
if ($mysqli->connect_error) {
    die('Errore di connessione (' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
}
 
// Creo il database
if ($mysqli->query("CREATE DATABASE IF NOT EXISTS " . $db_database)) {
	echo "<p style='font-weight:bold; color:green;'>Database creato correttamente</p>";
}
else {
	echo "<p style='font-weight:bold; color:red;'>Errore nella creazione del database!</p>";
}
 
// Seleziono il database
$mysqli->query("USE " . $db_database);

// Creo tabella "pazienti"

$drop = "DROP TABLE IF EXISTS pazienti";
$create = "CREATE TABLE IF NOT EXISTS pazienti (
  CodFiscale varchar(16) NOT NULL,
  Nome varchar(50) NOT NULL,
  Cognome varchar(50) NOT NULL,
  DataNascita varchar(10) NOT NULL,
  LuogoNascita varchar(50) NOT NULL,
  PRIMARY KEY (CodFiscale)
)";

if ($mysqli->query($drop) && $mysqli->query($create)) {
	echo "<p style='font-weight:bold; color:green;'>Tabella 'pazienti' creata correttamente</p>";
}
else {
	echo "<p style='font-weight:bold; color:red;'>Errore nella creazione della tabella 'pazienti'!</p>";
}


// Creo tabella "terapie"

$drop = "DROP TABLE IF EXISTS terapie";
$create = "CREATE TABLE IF NOT EXISTS terapie (
  IDterapia int(11) NOT NULL AUTO_INCREMENT,
  CodFiscale varchar(16) NOT NULL,
  NomeFarmaco varchar(50) NOT NULL,
  Quantita int(11) NOT NULL,
  TipoPosologia varchar(20) NOT NULL,
  Posologia TEXT NOT NULL,
  Orari varchar(80) NOT NULL,
  Inizio varchar(10) NOT NULL,
  Fine varchar(10) NOT NULL,
  PRIMARY KEY (IDterapia),
  FOREIGN KEY (CodFiscale) REFERENCES pazienti(CodFiscale) ON DELETE CASCADE
) AUTO_INCREMENT=1";

if ($mysqli->query($drop) && $mysqli->query($create)) {
	echo "<p style='font-weight:bold; color:green;'>Tabella 'terapie' creata correttamente</p>";
}
else {
	echo "<p style='font-weight:bold; color:red;'>Errore nella creazione della tabella 'terapie'!</p>";
}

?>