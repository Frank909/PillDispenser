<!DOCTYPE html>
<html lang="it" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pill Dispenser | Paziente</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    
</head>
<body>
    <header>
        <div class="container-header">
			<a href="index.php">
                <span class="back-button glyphicon white pull-right">Indietro</span>
            </a>
		</div>
    </header>
    <div class="container">
        <div class="col-sm-12 col-md-12 col-lg-12 margin-top-bottom-70">
            <div id="logo" class="text-center panel-title">
                <img src="images/logo.png" class="flex"/>
            </div>
        </div>
        <div id="inserisci-codfisc" class="col-sm-12 col-md-12 col-lg-12 ">
            <div class="text-center panel-title">
                <h3>Login parente</h3>
            </div>
        </div>
        <div class="col-sm-8 col-md-8 col-lg-8  col-sm-offset-2 col-md-offset-2 col-lg-offset-2 margin-top-bottom-20">
            <form action="parente_terapia.php" onsubmit="submitCodFiscale(); return false;" method="POST">
				<div class="input-group">
					<input id="cod_fiscale" type="text" placeholder="Inserisci codice fiscale del paziente" class="form-control input-lg" maxlength="16" onkeypress="dismissError();">
					<span class="input-group-btn"><button type="submit" onclick="submitCodFiscale(); return false;" class="btn input-lg btn-large btn-primary pd-button-flat">Continua</button></span>
				</div>
				<div id="codfisc-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Inserire un codice fiscale valido!</div>
            </form>
        </div>

    </div>
    <footer>
    </footer>
	
	<script type="text/javascript">
		function dismissError() {
            $("#codfisc-error").slideUp(100);
        }
		
		function submitCodFiscale() {
			var codfisc = document.getElementById('cod_fiscale').value;
			
			if (codfisc.length != 16) {
				$("#codfisc-error").html("<strong>Attenzione!</strong> Inserire un codice fiscale valido!");
				$("#codfisc-error").slideDown(300);
				return;
			}
				
			var http = new XMLHttpRequest();
			var url = "do.php";
			var params = "what=codfisc_exists&CodFiscale=" + codfisc;
			
			http.open("POST", url, true);

			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

			http.onreadystatechange = function() {
				if (http.readyState == 4 && http.status == 200) {
					
					if (http.responseText == "0") {
						$("#codfisc-error").html("Codice fiscale non presente nel database. Si prega di riprovare.");
						$("#codfisc-error").slideDown(300);
						return;
					}
					
					window.location.href = 'parente_terapia.php?id=' + codfisc;
				}
			}
			http.send(params);
		}
	</script>
</body>