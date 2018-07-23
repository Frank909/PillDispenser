<!DOCTYPE html>
<html lang="it" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pill Dispenser | Lista Pazienti</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>

    <script type="text/javascript">
    jQuery( function($) {
        $('tbody tr[data-href]').addClass('clickable').click( function() {
            window.location = $(this).attr('data-href');
        }).find('a').hover( function() {
            $(this).parents('tr').unbind('click');
        }, function() {
            $(this).parents('tr').click( function() {
                window.location = $(this).attr('data-href');
            });
        });
    });
</script>
</head>
<body>
    <header>
        <div class="container-header">
            <a href="index.php">
                <span class="exit-button glyphicon white pull-right">Esci</span>
            </a>
        </div>
        
    </header>
    <div class="container">
        <div class="col-sm-12 col-md-12 col-lg-12 margin-top-bottom-70">
            <div id="logo" class="text-center panel-title">
                <img src="images/logo.png" class="flex"/>
            </div>
        </div>
		
		<?php
		
			require_once("config.php");
			global $db_host, $db_user, $db_password, $db_database;

			$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
			if ($mysqli->connect_error) {
				die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
			}
			
			$result = $mysqli->query("SELECT * FROM pazienti");
			
			if (!$result)
				echo "<div class=\"col-sm-6 col-md-6 col-lg-6  col-sm-offset-3 col-md-offset-3 col-lg-offset-3 margin-top-bottom-20\"><div class=\"alert alert-danger fade in margin-top-bottom-20\"><strong>Errore MySQL!</strong> Ricaricare la pagina. Se l'errore persiste contattare il servizio clienti.</div></div>";
			
			elseif ($result->num_rows == 0)
				echo "<div class=\"text-center panel-title margin-top-bottom-20\"><h3>Nessun paziente presente</h3></div>";
			
			else {
		?>

        <div id="tabella_pazienti" class="col-sm-12 col-md-12 col-lg-12 margin-top-bottom-70">
				
				<div id="desktop">
					<?php viewDesktop($result)?>
				</div>

				<?php $result = $mysqli->query("SELECT * FROM pazienti"); ?>

				<div id="mobile">
					<?php viewMobile($result)?>
				</div>

        </div>
		
			<?php } ?>
		
        <div class=" col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <a href="form_paziente.php" class="btn btn-primary pd-button">Aggiungi Paziente</a>
            </div>
        </div>
    </div>
	
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Sei sicuro?</h4>
		  </div>
		  <div id="modal-body" class="modal-body"></div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
			<button id="confirm-delete" type="button" class="btn btn-primary">Elimina</button>
		  </div>
		</div>
	  </div>
	</div>
	
    <footer>
    </footer>
	
	<script type="text/javascript">
		function deleteModalData(codicefiscale) {
			$('#confirm-delete').removeAttr('onclick');
			$('#confirm-delete').attr("onclick", "deletePaziente('" + codicefiscale + "');");
			var nome = $("#" + codicefiscale + " .name-surname").html();
			$("#modal-body").html(nome + " sarà eliminato in maniera permanente dalla lista dei pazienti");
		}
		function deletePaziente(codicefiscale) {
			var http = new XMLHttpRequest();
				var url = "do.php";
				var params = "what=remove_paziente&CodFiscale=" + codicefiscale;
				
				http.open("POST", url, true);

				http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

				http.onreadystatechange = function() {
					if(http.readyState == 4 && http.status == 200) {
						window.location.href = 'tabella_pazienti.php';
					}
				}
				http.send(params);
		}
	</script>
</body>

<?php

	function viewDesktop($param)
	{
		echo '<table class="table table-striped table-responsive">';
            echo '<thead>';
                echo '<th>Nome e Cognome</th>';
                echo '<th>Data di Nascita</th>';
                echo '<th>Luogo di Nascita</th>';
                echo '<th>Codice Fiscale</th>';
				echo '<th></th>';
				echo '<th></th>';
                echo '</thead>';
				
                echo '<tbody class="table-bordered">';

		while($r = $param->fetch_assoc()) 
		{
			printf ("<tr id=\"%s\" data-href=\"tabella_terapie.php?id=%s\" >", $r["CodFiscale"], $r["CodFiscale"]);
			printf ("<td class=\"name-surname\">%s %s</td>", $r["Nome"], $r["Cognome"]);
			$newDate = date("d-m-Y", strtotime($r["DataNascita"]));
			printf ("<td>%s</td>", $newDate);
			printf ("<td>%s</td>", $r["LuogoNascita"]);
			printf ("<td>%s</td>", $r["CodFiscale"]);
			printf ("<td><a href=\"form_paziente.php?edit=%s\"><span class=\"glyphicon glyphicon glyphicon-pencil\"></span></a></td>", $r["CodFiscale"]);
			printf ("<td><a href=\"#\" onclick=\"deleteModalData('%s')\" data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"glyphicon glyphicon glyphicon-trash\"></span></a></td>", $r["CodFiscale"]);
			echo "</tr>";
		}

		echo '</tbody>';
        echo '</table>';
	}

	function viewMobile($param)
	{
		while($r = $param->fetch_assoc()) 
		{
			echo '<table class="table table-striped table-bordered table-responsive">';
            echo '<thead></thead>';
			echo '<tbody class="table-bordered">';
				echo "<tr>";
					printf ("<td class=\"text-center\"><a href=\"form_paziente.php?edit=%s\"><span class=\"glyphicon glyphicon glyphicon-pencil\"></span></a></td>", $r["CodFiscale"]);
					printf ("<td class=\"text-center\"><a href=\"#\" onclick=\"deleteModalData('%s')\" data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"glyphicon glyphicon glyphicon-trash\"></span></a></td>", $r["CodFiscale"]);
				echo "</tr>";
				printf ("<tr id=\"%s\" colspan=\"2\" data-href=\"tabella_terapie.php?id=%s\" >", $r["CodFiscale"], $r["CodFiscale"]);
				printf ("<td colspan=\"2\"class=\"name-surname\"><strong>%s %s</strong>", $r["Nome"], $r["Cognome"]);
				$newDate = date("d-m-Y", strtotime($r["DataNascita"]));
				printf ("<br>Data di Nascita: <strong>%s</strong>", $newDate);
				printf ("<br>Luogo di Nascita: <strong>%s</strong>", $r["LuogoNascita"]);
				printf ("<br>Codice Fiscale: <strong>%s</strong>", $r["CodFiscale"]);
				echo "</td>";
				echo "</tr>";
			echo '</tbody>';
        	echo '</table>';
		}
	}
?>