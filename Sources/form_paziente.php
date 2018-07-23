<!DOCTYPE html>
<html lang="it" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pill Dispenser | Gestione Paziente</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="bootstrap-datepicker/dist/css/bootstrap-datepicker.css"/>
    
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="bootstrap-datepicker/locales/bootstrap-datepicker.it.js" charset="UTF-8"></script>
    
    
    <script type="text/javascript">
		$(document).ready(function(){
			var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
			$('.input-group.date').datepicker({
				format: 'dd-mm-yyyy',
				container: container,
				todayHighlight: true,
				autoclose: true,
				language: "it",
			})
		})
    </script>
</head>
<body>
    <header>
        <div class="container-header">
            <a href="index.php">
                <span class="exit-button glyphicon white pull-right">Esci</span>
            </a>
        </div>
		
		<?php
		
		$insert = true;
		
		if (isset($_GET["edit"])) {
			
			require_once('config.php');
			global $db_host, $db_user, $db_password, $db_database;
	
			$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
			if ($mysqli->connect_error) {
				die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
			}
			
			$stmt = $mysqli->prepare("SELECT * FROM pazienti WHERE CodFiscale=?");
			$stmt->bind_param("s", $_GET["edit"]);
					
			$result = $stmt->execute();
			
			if ($result) {
				$insert = false;
				
				$stmt->bind_result($cd_fisc, $nome, $cognome, $d_nascita, $l_nascita);
				$stmt->fetch();
			}
			
		}
		
		?>
        
    </header>
    <div class="container">
        <div class="col-sm-12 col-md-12 col-lg-12 margin-top-bottom-70">
            <div id="logo" class="text-center panel-title">
                <img src="images/logo.png" class="flex"/>
            </div>
        </div>

        <div id="form-paziente" class="col-sm-12 col-md-12 col-lg-12">
        <h3 class="text-center"><?php if(isset($cd_fisc)) echo 'Modifica Paziente'; else echo 'Aggiungi Paziente';?></h3>
            <form class="form-group" onsubmit="checkAndSend(); return false;">
				<input id="submit_type" type="hidden" name="submit_type" value="<?php if ($insert) echo "insert"; else echo "edit"; ?>">
                <div class="form-group">
                    <label class="control-label">Nome</label>
                    <input id="nome" onkeypress="hideError('#nome-error')" type="text" class="form-control input-lg" name="Nome" <?php if(isset($nome)) printf("value=\"%s\"", $nome); ?> placeholder="Inserisci il nome del paziente" />
					<div id="nome-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Il campo "Nome" non può essere lasciato vuoto!</div>
                </div>
                <div class="form-group">
                    <label class="control-label">Cognome</label>
                    <input id="cognome" onkeypress="hideError('#cognome-error')" type="text" class="form-control input-lg" name="Cognome" <?php if(isset($cognome)) printf("value=\"%s\"", $cognome); ?> placeholder="Inserisci il cognome del paziente" />
                    <div id="cognome-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Il campo "Cognome" non può essere lasciato vuoto!</div>
                </div>
                <div class="form-group">
                    <label class="control-label">Data di Nascita</label>
                    <div class='input-group date'>
                        <span class="input-group-addon input-lg">
                                <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <input id="date" onfocus="hideError('#datanascita-error')" name="date" class="form-control input-lg" <?php if(isset($d_nascita)) printf("value=\"%s\"", date("d-m-Y", strtotime($d_nascita))); ?> placeholder="Clicca qui per scegliere la data" type="text" readonly/>
                    </div>
					<div id="datanascita-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Il campo "Data di nascita" non può essere lasciato vuoto!</div>
                </div>
                <div class="form-group">
                    <label class="control-label">Luogo di Nascita</label>
                    <input id="luogonascita" onkeypress="hideError('#luogonascita-error')" type="text" class="form-control input-lg" name="Luogo di nascita" <?php if(isset($l_nascita)) printf("value=\"%s\"", $l_nascita); ?> placeholder="Inserisci il luogo di nascita del paziente" />
                    <div id="luogonascita-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Il campo "Luogo di nascita" non può essere lasciato vuoto!</div>
                </div>
                <div class="form-group">
                    <label class="control-label">Codice Fiscale</label>
                    <input id="codfiscale" onkeypress="hideError('#codfiscale-error')" type="text" class="form-control input-lg" name="Codice Fiscale" <?php if(isset($cd_fisc)) printf("value=\"%s\"", $cd_fisc); ?> placeholder="Inserisci il codice fiscale del paziente" maxlength="16"/>
                    <div id="codfiscale-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Il campo "Codice Fiscale" è errato! Il Codice Fiscale ha una lunghezza di 16 caratteri.</div>
                </div>
            </form>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
            <div class="margin-top-bottom-20">
                	<?php echo '<button onclick="checkAndSend(\'';
                		if(isset($cd_fisc))
                			echo $cd_fisc;
                		echo '\'); return false;" class="btn btn-primary pd-button">';
                        if(isset($cd_fisc)) echo 'Modifica Paziente'; else echo 'Aggiungi Paziente';
                        echo '</span>'; ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="col-sm-offset-3 col-md-offset-3 col-lg-offset-3 margin-top-bottom-20">
             	<a href="tabella_pazienti.php" class="btn btn-danger pd-button">Annulla</a>
        	</div>
    	</div>
    <footer>
    </footer>
	
	<script type="text/javascript">
		function checkAndSend() {
			
			var onlyLetters  = /^[a-zA-Z\s]*$/;
			var nome         = document.getElementById('nome').value;
			var cognome      = document.getElementById('cognome').value;
			var datanascita  = document.getElementById('date').value;
			var luogonascita = document.getElementById('luogonascita').value;
			var codfiscale   = document.getElementById('codfiscale').value;
			
			var control = true;
			
			if (nome == "") {
				$("#nome-error").html("<strong>Attenzione!</strong> Il campo \"Nome\" non può essere lasciato vuoto!");
				$("#nome-error").slideDown(300);
				control = false;
			}
			if (!onlyLetters.test(nome)) {
				$("#nome-error").html("<strong>Attenzione!</strong> Il campo \"Nome\" può contenere solo lettere!");
				$("#nome-error").slideDown(300);
				control = false;
			}
			
			if (cognome == "") {
				$("#cognome-error").html("<strong>Attenzione!</strong> Il campo \"Cognome\" non può essere lasciato vuoto!");
				$("#cognome-error").slideDown(300);
				control = false;
			}
			if (!onlyLetters.test(cognome)) {
				$("#cognome-error").html("<strong>Attenzione!</strong> Il campo \"Cognome\" può contenere solo lettere!");
				$("#cognome-error").slideDown(300);
				control = false;
			}
			
			if (datanascita == "") {
				$("#datanascita-error").html("<strong>Attenzione!</strong> Il campo \"Data di nascita\" non può essere lasciato vuoto!");
				$("#datanascita-error").slideDown(300);
				control = false;
			}
			else {
				var day   = parseInt(datanascita.substring(0,2));
				var month = parseInt(datanascita.substring(3,5)) - 1;
				var year  = parseInt(datanascita.substring(6,10));
				
				if (!(new Date(year, month, day) < new Date())) {
					$("#datanascita-error").html("<strong>Attenzione!</strong> Inserire una data valida, precedente a quella di oggi!");
					$("#datanascita-error").slideDown(300);
					control = false;
				}
			}
			
			if (luogonascita == "") {
				$("#luogonascita-error").html("<strong>Attenzione!</strong> Il campo \"Luogo di nascita\" non può essere lasciato vuoto!");
				$("#luogonascita-error").slideDown(300);
				control = false;
			}
			if (!onlyLetters.test(luogonascita)) {
				$("#luogonascita-error").html("<strong>Attenzione!</strong> Il campo \"Luogo di nascita\" può contenere solo lettere!");
				$("#luogonascita-error").slideDown(300);
				control = false;
			}
			
			if (codfiscale.length != 16) {
				$("#codfiscale-error").slideDown(300);
				control = false;
			}
			
			if (control) {
				// Tutto ok, quindi esegui
				
				var mode = document.getElementById('submit_type').value;
				
				var http = new XMLHttpRequest();
				var url = "do.php";
				var params = "&CodFiscale=" + codfiscale + "&Nome=" + nome + "&Cognome=" + cognome + "&DataNascita=" + datanascita + "&LuogoNascita=" + luogonascita;
				
				if (mode == "insert")
					params = "what=add_paziente" + params;
				else if (mode == "edit")
					params = "what=edit_paziente" + params;
				
				http.open("POST", url, true);

				http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

				http.onreadystatechange = function() {
					if(http.readyState == 4 && http.status == 200) {
						window.location.href = 'tabella_pazienti.php';
					}
				}
				http.send(params);
			}
		}
		
		function hideError($id_name) {
			$($id_name).slideUp(100);
		}
	</script>
</body>

