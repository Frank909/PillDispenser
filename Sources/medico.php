<!DOCTYPE html>
<html lang="it" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pill Dispenser | Medico</title>
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
        <div id="inserisci-pin" class="col-sm-12 col-md-12 col-lg-12 ">
            <div class="text-center panel-title">
                <h3>Login medico</h3>
            </div>
        </div>
        <div class="col-sm-8 col-md-8 col-lg-8  col-sm-offset-2 col-md-offset-2 col-lg-offset-2 margin-top-bottom-20">
            <form id="pin-form" action="tabella_pazienti.php" method="POST" onsubmit="checkPIN(); return false;">
				<div class="input-group">
					<input id="pin" type="password" onkeypress="hideError()" class="form-control input-lg" placeholder="Inserisci il PIN...">
					<span class="input-group-btn">
						<button class="btn input-lg btn-large btn-primary pd-button-flat" type="submit">Accedi</button>
					</span>
				</div>
				
				<div id="pin-error" style="display:none;" class="alert alert-danger fade in margin-top-bottom-20">
					<strong>Attenzione!</strong> Il PIN inserito è errato. Riprovare.
				</div>
            </form>
        </div>
		
    </div>
    <footer>
    </footer>
	
	<script type="text/javascript">
		function checkPIN() {
			if (document.getElementById('pin').value == 1234)
				$("#pin-form").submit();
			else
				$("#pin-error").slideDown(300);
		}
		function hideError() {
			$("#pin-error").slideUp(100);
		}
	</script>
</body>