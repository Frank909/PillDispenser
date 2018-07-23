<!DOCTYPE html>
<html lang="it" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pill Dispenser | Gestione Terapia</title>
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

    <script type="text/javascript">

    var counter = 1;

    function addTime(param)
    {
        
        if(counter<12){
            counter++;
            $(param).prepend('<div id="newTime'+counter+'" class="form-group"><div class="input-group"><span class="input-group-addon"><a onclick=\'removeTime("#newTime'+counter+'")\'><span class="glyphicon glyphicon glyphicon-remove"></span></a><span>&nbsp Orario</span></span><input id="timepicker'+counter+'" type="time" class="form-control input-lg"/></div></div>');
        }
        else return;
        if(counter==12)
            $("#btnID").hide("slow");
    }

    function removeTime(param)
    {
        $(param).remove();
        counter--;
        if(counter<12)
            $("#btnID").show("slow");
    }

    function posologia(param)
    {
        if(param == 'Mensile')
        {
            $('#settimanale').hide();
            $('#mensile').show();
        }else if(param == 'Settimanale')
        {
            $('#mensile').hide();
            $('#settimanale').show();
        }    
    }
    </script>

    <script type="text/javascript">
    $(function() {
    	$('#search').on('keyup', function() {
        	var pattern = $(this).val();
        	$('.searchable-container .items').hide();
        	$('.searchable-container .items').filter(function() {
            	return $(this).text().match(new RegExp(pattern, 'i'));
        	}).show();
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
        
        <?php
        
        $insert = true;
        
        if (isset($_GET["edit"])) {
            
            require_once('config.php');
            global $db_host, $db_user, $db_password, $db_database;
    
            $mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
            if ($mysqli->connect_error) {
                die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
            }
            
            $stmt = $mysqli->prepare("SELECT * FROM terapie WHERE IDterapia=?");
            $stmt->bind_param("s", $_GET["edit"]);
                    
            $result = $stmt->execute();
            
            if ($result) {
                $insert = false;
                
                $stmt->bind_result($idterapia, $cod_fiscale, $nome_farmaco, $quantita, $tipo_posologia, $posologia, $orari, $inizio, $fine);
                $stmt->fetch();
            }
            
        }

        if(isset($_GET["id"]))
            $codFiscale = $_GET["id"];
        ?>

    </header>
    <div class="container">
        <div class="col-sm-12 col-md-12 col-lg-12 margin-top-bottom-70">
            <div id="logo" class="text-center panel-title">
                <img src="images/logo.png" class="flex"/>
            </div>
        </div>

        <div id="form-paziente" class="col-sm-12 col-md-12 col-lg-12">
            <h3 class="text-center"><?php if(isset($idterapia)) echo 'Modifica Terapia'; else echo 'Aggiungi Terapia'; ?></h3>
            <form class="form-group">
                <input id="submit_type" type="hidden" name="submit_type" value="<?php if ($insert) echo "insert"; else echo "edit"; ?>">
                <div class="form-group">
                    <label class="control-label">Nome Farmaco</label>
                    <input id="nome_farmaco" type="text" onkeypress="hideError('#farmaco-error')" class="form-control input-lg" name="Nome" <?php if(isset($nome_farmaco)) printf("value=\"%s\"", $nome_farmaco); ?> placeholder="Inserisci il nome del farmaco" />
                    <div id="farmaco-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Il campo "Nome Farmaco" non può essere lasciato vuoto!</div>
                </div>
                <div class="form-group">
                    <label class="control-label">Numero dosi</label>
					<input id="quantita" class="form-control input-lg" type="number" min="1" max="99" <?php if(isset($quantita)) printf("value=\"%d\"", $quantita); else printf("value=\"1\""); ?> />
                    <div id="quantita-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Inserire una quantità di somministrazione valida! (Minimo: 1, Massimo: 99)</div>

                    <!-- Vecchia versione con select
					<select id="quantita" class="form-control input-lg">
                        <option <?php if(isset($quantita) && $quantita == "1") echo("selected"); ?>>1</option>
                        <option <?php if(isset($quantita) && $quantita == "2") echo("selected"); ?>>2</option>
                        <option <?php if(isset($quantita) && $quantita == "3") echo("selected"); ?>>3</option>
                        <option <?php if(isset($quantita) && $quantita == "4") echo("selected"); ?>>4</option>
                        <option <?php if(isset($quantita) && $quantita == "5") echo("selected"); ?>>5</option>
                    </select>
					-->
                </div>
                <div class="form-group margin-bottom-70">
                    <label class="control-label">Tipo di Posologia</label>
                    <select id="tipo_posologia"class="form-control input-lg" onchange="posologia(this.value)">
                        <option <?php if(isset($tipo_posologia) && $tipo_posologia == "Settimanale") echo("selected"); ?>>Settimanale</option>
                        <option <?php if(isset($tipo_posologia) && $tipo_posologia == "Mensile") echo("selected"); ?>>Mensile</option>
                    </select>
                </div>

                <fieldset>
                    <legend>Posologia</legend>

                    <?php
                            $week = ["null","Lun","Mar","Mer","Gio","Ven","Sab","Dom"];
                            
                            if(isset($posologia))
                            {
                                $week_or_month;
                                $temp;
                                $temp = $posologia;
                                $week_or_month = explode(",", $temp);
                                $week_or_month_length = count($week_or_month);
                            }
                    ?>

                    <div id="settimanale" <?php if(isset($posologia) && $tipo_posologia == "Mensile") echo 'style="display:none;"'; ?>>
                        <div class="form-group" style="margin: 0 0 30px 0;">
                            <?php for($i=1; $i<8; $i++){
								echo '<div class="checkbox week col-xs-3 col-sm-1 col-md-1 col-lg-1">';
							        echo '<label class="size1dot5em">';
							            echo '<input id="week'.$i.'" type="checkbox" value="" ';
                                            if(isset($posologia) && $week_or_month_length == 8 && $week_or_month[$i] != "0") 
                                                    echo('checked');
                                        echo '>';
							            echo '<span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>';
							            echo '<br>';
                                        echo $week[$i];
							        echo '</label>';
							    echo '</div>';
                            }?>
                        </div>
                    </div>

                    <div id="mensile" <?php if(isset($posologia) && $tipo_posologia == "Mensile") echo 'style="display:block;"'; ?>>
                        <div class="form-group">
                            <div class="checkbox col-sm-12 col-md-12 col-lg-12 margin-bottom-50">
            					<div class="searchable-container">
                					<?php for($i=1; $i<29; $i++){
                                    echo '<div class="items col-xs-2 col-sm-1dot8 col-md-1dot8 col-lg-1dot8">';
                        				echo '<div data-toggle="buttons" class="btn-group bizmoduleselect">';
                            				echo '<label class="btn btn-default ';
                                                if(isset($posologia) && $week_or_month_length == 29 && $week_or_month[$i] != "0") 
                                                            echo('active');
                                            echo '">';
                                				echo '<div class="bizcontent">';
                                    				echo '<input id="month'.$i.'" type="checkbox" name="var_id[]" autocomplete="off" value="" ';
                                                        if(isset($posologia) && $week_or_month_length == 29 && $week_or_month[$i] != "0") 
                                                            echo('checked');
                                                    echo '>';
                                    				echo '<span class="glyphicon glyphicon-ok glyphicon-lg"></span>';
                                    				echo '<h5>';
                                                    echo $i;
                                                    echo '</h5>';
                                				echo '</div>';
                            				echo '</label>';
                        				echo '</div>';
                					echo '</div>';
                                    }?>
								</div>    
                        	</div>
                        </div>    
                    </div>

                </fieldset>

                <div class="form-group margin-bottom-70">

                <?php 
                    if(isset($orari))
                    {
                        $j=1;
                        $orari_caricati = explode(",", $orari);
                        for($i=1;$i<count($orari_caricati);$i++)
                        {
                            if ($orari_caricati[$i] != "")
                            {
                                $new_orari_caricati[$j] = $orari_caricati[$i];
                                $j++;
                            }
                        }

                        $orari_caricati_length = count($new_orari_caricati);

                        for($i=1; $i<=$orari_caricati_length; $i++)
                        {
                            echo '<div id="settimanale-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Seleziona almeno un giorno della settimana!</div>';
                            echo '<div id="newTime'.$i.'" class="form-group">';
                                echo' <div class=\'input-group\'>';
                                    echo '<span class="input-group-addon">';
                                        echo '<a onclick="removeTime(\'#newTime'.$i.'\')">';
                                            echo '<span class="glyphicon glyphicon glyphicon-remove"></span>';
                                        echo '</a>';
                                        echo '<span>&nbspOrario</span>';        
                                    echo '</span>';
                                    echo '<input id="timepicker'.$i.'" type="time" class="form-control input-lg" ';
                                        printf("value=\"%s\"", $new_orari_caricati[$i]);
                                    echo ' onfocus="hideError(\'#orari-error\')"/>';
                                echo '</div>';
                            echo '</div>';
                        }
                        
                    }else
                    {
                        echo '<div id="settimanale-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Seleziona almeno un giorno della settimana!</div>';
                        echo '<div id="newTime1" class="form-group">';
                            echo' <div class=\'input-group\'>';
                                echo '<span class="input-group-addon">';
                                    echo '<a onclick="removeTime(\'#newTime1\')">';
                                        echo '<span class="glyphicon glyphicon glyphicon-remove"></span>';
                                    echo '</a>';
                                    echo '<span>&nbspOrario</span>';        
                                echo '</span>';
                                echo '<input id="timepicker1" type="time" class="form-control input-lg" onfocus="hideError(\'#orari-error\')"/>';
                            echo '</div>';
                        echo '</div>';
                    }
                ?>
					<div id="btnAddTimeWeek">
						<div id="btnID" class='input-group'>
							<a onclick="addTime('#btnAddTimeWeek')">
								<span class="btn btn-primary btn-lg">
									<span class="glyphicon glyphicon glyphicon glyphicon-plus"></span>
									<span>&nbsp Aggiungi Orario</span>
								</span>
							</a>
						</div>
					</div>
					<div id="orari-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Seleziona almeno un orario!</div>
                </div>

                <?php 
                    if(isset($inizio))
                    {
                        echo '<div class="form-group">';
                            echo '<label class="control-label">Data di Inizio Terapia</label>';
                            echo '<div class=\'input-group date\' id=\'datetimepicker\'>';
                                echo '<span class="input-group-addon input-lg">';
                                        echo '<span class="glyphicon glyphicon-calendar"></span>';
                                echo '</span>';
                                echo '<input id="dateStart" onchange="dd-mm-yyyy" onfocus="hideError(\'#inizio-error\')" name="date" class="form-control input-lg"';
                                    printf("value=\"%s\"", date("d-m-Y", strtotime($inizio)));
                                echo 'placeholder="Cliccare per selezionare la data di inizio terapia" type="text" disabled/>';
                            echo '</div>';
                        echo '</div>';
                    }else
                    {
                        echo '<div class="form-group">';
                            echo '<label class="control-label">Data di Inizio Terapia</label>';
                            echo '<div class=\'input-group date\' id=\'datetimepicker\'>';
                                echo '<span class="input-group-addon input-lg">';
                                        echo '<span class="glyphicon glyphicon-calendar"></span>';
                                echo '</span>';
                                echo '<input id="dateStart" onfocus="hideError(\'#inizio-error\')" name="date" class="form-control input-lg" placeholder="Cliccare per selezionare la data di inizio terapia" type="text"/>';
                            echo '</div>';
                            echo '<div id="inizio-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Selezionare una data di inizio!</div>';
                        echo '</div>';
                    }
                    
                ?>

                <div class="form-group">
                    <label class="control-label">Data di Fine Terapia</label>
                    <div class='input-group date' id='datetimepicker'>
                        <span class="input-group-addon input-lg">
                                <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <input id="dateEnd" onfocus="hideError('#fine-error')" name="date" class="form-control input-lg" <?php if(isset($fine)) printf("value=\"%s\"", date("d-m-Y", strtotime($fine))); ?> placeholder="Cliccare per selezionare la data di fine terapia" type="text" readonly/>     
                    </div>
                    <div id="fine-error" style="margin: 10px 0 30px 0; display:none;" class="alert alert-danger fade in"><strong>Attenzione!</strong> Seleziona una data valida di fine!</div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                    <div class="margin-top-bottom-20">
                        <?php echo '<button onclick="checkAndSend(\'';
                                if(isset($codFiscale))
                                    echo $codFiscale;
                              echo '\',\'';
                                if(isset($idterapia))
                                    echo $idterapia;
                              echo '\'); return false;" class="btn btn-primary pd-button">';
                                if(isset($idterapia)) echo 'Modifica Terapia'; else echo 'Aggiungi Terapia';
                              echo '</span>'; ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="margin-top-bottom-20">
                        <?php echo '<a href="tabella_terapie.php?id='.$codFiscale.'" class="btn btn-danger pd-button">Annulla</a>'; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <footer>
    </footer>

    <script type="text/javascript">
        function checkAndSend(param_codfiscale, IDterapia) {

            var nome_farmaco   = document.getElementById('nome_farmaco').value;
            var quantita       = document.getElementById('quantita').value;
            var tipo_posologia = document.getElementById('tipo_posologia').value;
            var inizio         = document.getElementById('dateStart').value;
            var fine           = document.getElementById('dateEnd').value;
            var posologia;
            var time;

            var dayChecked    = new Array();
            var monthChecked  = new Array();
            var newTime       = new Array();
            var newMonthTime  = new Array();
            var dayFlag       = false,
			    monthFlag     = false,
				timeFlag      = false,
				timeMonthFlag = false;

            for (i=1; i<8; i++)
                dayChecked[i] = 0;

            for(i=1; i<8; i++) {
                if(document.getElementById('week' + i).checked)
                    dayChecked[i] = i;
                if(dayChecked[i] != 0)
                    dayFlag = true;
            }

            for(i=1; i<29; i++)
                monthChecked[i] = 0;

            for(i=1; i<29; i++){
                if(document.getElementById('month' + i).checked)
                    monthChecked[i] = i;
                if(monthChecked[i] != 0)
                    monthFlag = true;
            }

            for(i=1; i<11; i++){
                if(document.getElementById('timepicker' + i) != null)
                    newTime[i] = document.getElementById('timepicker' + i).value;
                else newTime[i] = "";
                if(newTime[i] != "")
                    timeFlag = true;
            }

            if (tipo_posologia === 'Settimanale')
                posologia = dayChecked.toString();
			else
                posologia = monthChecked.toString();
			
            time = newTime.toString();

            var control = true;
            
            if (quantita<1 || quantita>99) {
                $("#quantita-error").slideDown(300);
                control = false;
            }

            if (nome_farmaco == "") {
                $("#farmaco-error").slideDown(300);
                control = false;
            }
            
            if(tipo_posologia == "Settimanale" && dayFlag == false) {
                $("#settimanale-error").slideDown(300);
                control = false;
            }

            if (timeFlag == false) 
            {
                $("#orari-error").slideDown(300);
                control = false;                 
            }

            if (inizio == "") {
				$("#inizio-error").html("<strong>Attenzione!</strong> Selezionare una data di inizio terapia!");
                $("#inizio-error").slideDown(300);
                control = false;
            }
			else {
				var day   = parseInt(inizio.substring(0,2));
				var month = parseInt(inizio.substring(3,5)) - 1;
				var year  = parseInt(inizio.substring(6,10));
				
				if ((new Date(year, month, day+1) < new Date()) && !($("#dateStart").attr('disabled'))) {
					$("#inizio-error").html("<strong>Attenzione!</strong> La data di inizio terapia non può essere nel passato!");
					$("#inizio-error").slideDown(300);
					control = false;
				}
			}

            if (fine == "") {
				$("#fine-error").html("<strong>Attenzione!</strong> Selezionare una data di fine terapia!");
                $("#fine-error").slideDown(300);
                control = false;
            }
			else {
				var dayStart   = parseInt(inizio.substring(0,2));
				var monthStart = parseInt(inizio.substring(3,5)) - 1;
				var yearStart  = parseInt(inizio.substring(6,10));
				
				var dayEnd     = parseInt(fine.substring(0,2));
				var monthEnd   = parseInt(fine.substring(3,5)) - 1;
				var yearEnd    = parseInt(fine.substring(6,10));
				
				if (new Date(yearEnd, monthEnd, dayEnd) < new Date(yearStart, monthStart, dayStart)) {
					$("#fine-error").html("<strong>Attenzione!</strong> La data di fine terapia deve essere in un giorno successivo alla data di inizio terapia!");
					$("#fine-error").slideDown(300);
					control = false;
				}
			}

            
            if (control) {
                // Tutto ok, quindi esegui
                var mode = document.getElementById('submit_type').value;
                
                var http = new XMLHttpRequest();
                var url = "do.php";

                if (mode == "insert")
                {
                    var params = "&CodFiscale=" + param_codfiscale + "&NomeFarmaco=" + nome_farmaco + "&Quantita=" + quantita + "&TipoPosologia=" + tipo_posologia + "&Posologia=" + posologia + "&Orari=" + newTime + "&Inizio=" + inizio + "&Fine=" + fine;
                    params = "what=add_terapia" + params;
                }
                else if (mode == "edit")
                {
                    var params = "&NomeFarmaco=" + nome_farmaco + "&Quantita=" + quantita + "&TipoPosologia=" + tipo_posologia + "&Posologia=" + posologia + "&Orari=" + newTime + "&Inizio=" + inizio + "&Fine=" + fine + "&IDterapia=" + IDterapia;
                    params = "what=edit_terapia" + params;
                }
                http.open("POST", url, true);

                http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                http.onreadystatechange = function() {
                    if(http.readyState == 4 && http.status == 200) {
                        window.location.href = 'tabella_terapie.php?id=' + param_codfiscale;
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