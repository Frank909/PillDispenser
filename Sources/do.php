<?php

require_once('config.php');

function codfisc_exists($cod_fiscale) {
	global $db_host, $db_user, $db_password, $db_database;
	
	$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
	if ($mysqli->connect_error) {
		die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	
	$stmt = $mysqli->prepare("SELECT * FROM pazienti WHERE CodFiscale = ?");
	$stmt->bind_param("s", $cod_fiscale);
	
	$stmt->execute();
	$stmt->store_result();
	
	echo $stmt->num_rows;
	$stmt->close();
	$mysqli->close();
}

function add_paziente($cod_fiscale, $nome, $cognome, $data_nascita, $luogo_nascita) {
	global $db_host, $db_user, $db_password, $db_database;
	
	$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
	if ($mysqli->connect_error) {
		die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	 
	$stmt = $mysqli->prepare("INSERT INTO pazienti (CodFiscale, Nome, Cognome, DataNascita, LuogoNascita) VALUES (?,?,?,STR_TO_DATE(?,'%d-%m-%Y'),?)");
	$stmt->bind_param("sssss", $cod_fiscale, $nome, $cognome, $data_nascita, $luogo_nascita);
	if ($stmt->execute())
		echo "Success";
	else
		echo "<p style='font-weight:bold; color:red;'>Errore nell'inserimento</p><p>" . $stmt->error . "</p>";
	
	$stmt->close();
	$mysqli->close();
}

function edit_paziente($cod_fiscale, $nome, $cognome, $data_nascita, $luogo_nascita) {
	global $db_host, $db_user, $db_password, $db_database;
	
	$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
	if ($mysqli->connect_error) {
		die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	
	$stmt = $mysqli->prepare("UPDATE pazienti SET CodFiscale = ?, Nome = ?, Cognome = ?, DataNascita = ?, LuogoNascita = ? WHERE CodFiscale = ?");
	$stmt->bind_param("ssssss", $cod_fiscale, $nome, $cognome, $data_nascita, $luogo_nascita, $cod_fiscale);
	if ($stmt->execute())
		echo "Success";
	else
		echo "<p style='font-weight:bold; color:red;'>Errore nella modifica</p><p>" . $stmt->error . "</p>";
	
	$stmt->close();
	$mysqli->close();
}

function remove_paziente($cod_fiscale) {
	global $db_host, $db_user, $db_password, $db_database;
	
	$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
	if ($mysqli->connect_error) {
		die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	
	$stmt = $mysqli->prepare("DELETE FROM pazienti WHERE CodFiscale = ?");
	$stmt->bind_param("s", $cod_fiscale);
	if ($stmt->execute())
		echo "Success";
	else
		echo "<p style='font-weight:bold; color:red;'>Errore nell'eliminazione</p><p>" . $stmt->error . "</p>";
	
	$stmt->close();
	$mysqli->close();
}

function add_terapia($cod_fiscale, $nome_farmaco, $quantita, $tipo_posologia, $posologia, $orari, $inizio, $fine) {
	global $db_host, $db_user, $db_password, $db_database;
	
	$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
	if ($mysqli->connect_error) {
		die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	 
	$stmt = $mysqli->prepare("INSERT INTO terapie (CodFiscale, NomeFarmaco, Quantita, TipoPosologia, Posologia, Orari, Inizio, Fine) VALUES (?,?,?,?,?,?,STR_TO_DATE(?,'%d-%m-%Y'),STR_TO_DATE(?,'%d-%m-%Y'))");
	$stmt->bind_param("ssisssss", $cod_fiscale, $nome_farmaco, $quantita, $tipo_posologia, $posologia, $orari, $inizio, $fine);
	if ($stmt->execute())
		echo "Success";
	else
		echo "<p style='font-weight:bold; color:red;'>Errore nell'inserimento</p><p>" . $stmt->error . "</p>";
	
	$stmt->close();
	$mysqli->close();
}

function edit_terapia($id_terapia, $nome_farmaco, $quantita, $tipo_posologia, $posologia, $orari, $inizio, $fine) {

	global $db_host, $db_user, $db_password, $db_database;
	
	$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
	if ($mysqli->connect_error) {
		die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	
	$stmt = $mysqli->prepare("UPDATE terapie SET NomeFarmaco = ?, Quantita = ?, TipoPosologia = ?, Posologia = ?, Orari = ?, Inizio = ?, Fine = ? WHERE IDterapia = ?");
	$stmt->bind_param("sisssssi", $nome_farmaco, $quantita, $tipo_posologia, $posologia, $orari, $inizio, $fine, $id_terapia);
	if ($stmt->execute()){
		echo "Success";
	}
	else{
		echo "<p style='font-weight:bold; color:red;'>Errore nella modifica</p><p>" . $stmt->error . "</p>";
	}
	
	$stmt->close();
	$mysqli->close();
}

function remove_terapia($id_terapia) {
	global $db_host, $db_user, $db_password, $db_database;
	
	$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
	if ($mysqli->connect_error) {
		die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	
	$stmt = $mysqli->prepare("DELETE FROM terapie WHERE IDterapia = ?");
	$stmt->bind_param("i", $id_terapia);
	if ($stmt->execute())
		echo "Success";
	else
		echo "<p style='font-weight:bold; color:red;'>Errore nell'eliminazione</p><p>" . $stmt->error . "</p>";
	
	$stmt->close();
	$mysqli->close();
}

if ($_POST) {
	switch ($_POST["what"]) {
		case "add_paziente":
			add_paziente($_POST["CodFiscale"], $_POST["Nome"], $_POST["Cognome"], $_POST["DataNascita"], $_POST["LuogoNascita"]);
			break;
		
		case "edit_paziente":
			edit_paziente($_POST["CodFiscale"], $_POST["Nome"], $_POST["Cognome"], $_POST["DataNascita"], $_POST["LuogoNascita"]);
			break;
			
		case "remove_paziente":
			remove_paziente($_POST["CodFiscale"]);
			break;

		case "add_terapia":
			add_terapia($_POST["CodFiscale"], $_POST["NomeFarmaco"], $_POST["Quantita"], $_POST["TipoPosologia"], $_POST["Posologia"], $_POST["Orari"], $_POST["Inizio"], $_POST["Fine"]);
			break;

		case "edit_terapia":
			edit_terapia($_POST["IDterapia"], $_POST["NomeFarmaco"], $_POST["Quantita"], $_POST["TipoPosologia"], $_POST["Posologia"], $_POST["Orari"], $_POST["Inizio"], $_POST["Fine"]);
			break;

		case "remove_terapia":
			remove_terapia($_POST["IDterapia"]);
			break;
			
		case "codfisc_exists":
			codfisc_exists($_POST["CodFiscale"]);
			break;
	}
}	
?>