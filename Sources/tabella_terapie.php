<!DOCTYPE html>
<html lang="it" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pill Dispenser | Terapia del Paziente</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();
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
            if(isset($_GET["id"]))
                $codFiscale = $_GET["id"];


                require_once("config.php");
                global $db_host, $db_user, $db_password, $db_database;

                $mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
                if ($mysqli->connect_error) {
                    die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
                }
            
                $resultPaziente = $mysqli->query("SELECT * FROM pazienti WHERE CodFiscale='".$codFiscale."'");
                $resultTerapie = $mysqli->query("SELECT * FROM terapie WHERE CodFiscale='".$codFiscale."'");

                if (!$resultPaziente)
                    echo "<div class=\"col-sm-6 col-md-6 col-lg-6  col-sm-offset-3 col-md-offset-3 col-lg-offset-3 margin-top-bottom-20\"><div class=\"alert alert-danger fade in margin-top-bottom-20\"><strong>Errore MySQL!</strong> Ricaricare la pagina. Se l'errore persiste contattare il servizio clienti.</div></div>";
                elseif ($resultTerapie->num_rows == 0)
                    echo "<div class=\"text-center panel-title margin-top-bottom-20\"><h3>Nessuna terapia presente</h3></div>";        
                else {
					$row = $resultPaziente->fetch_assoc();
        ?>

        <div id="tabella_paziente" class="col-sm-12 col-md-12 col-lg-12 margin-bottom-70">
            <h3>Terapie di:<br><?php printf("%s %s", $row["Nome"], $row["Cognome"]); ?> <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-html="true" data-placement="top" title="<?php printf("Nato il %s a %s<br/>Codice fiscale: %s",date("d-m-Y", strtotime($row["DataNascita"])) , $row["LuogoNascita"], $row["CodFiscale"]); ?>"></span></h3>

            <?php } ?> 

            <div id="desktop">
                <?php viewDesktop($resultTerapie); ?>
            </div>

            <?php $resultTerapie = $mysqli->query("SELECT * FROM terapie WHERE CodFiscale='".$codFiscale."'"); ?>

            <div id="mobile">
                <?php viewMobile($resultTerapie); ?>        
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                <div>
                <?php
                    printf("<a href=\"form_terapia.php?id=%s\" class=\"btn btn-primary pd-button\">Aggiungi Terapia</a>", $codFiscale);
                ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 margin-back-20">
                <div class="col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
                    <a href="tabella_pazienti.php" class="btn btn-primary pd-button">Torna a Lista Pazienti</a>
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

    </div>
    <footer>
    </footer>

    <script type="text/javascript">
        function deleteModalData(IDterapia, codicefiscale, NomeFarmaco) {
            $('#confirm-delete').removeAttr('onclick');
            $('#confirm-delete').attr("onclick", "deleteTerapia('" + IDterapia + "', '" + codicefiscale + "');");
            $("#modal-body").html("La terapia " + NomeFarmaco + " sarà eliminata in maniera permanente dalla lista dei pazienti");
        }

        function deleteTerapia(IDterapia, codicefiscale) {
            var http = new XMLHttpRequest();
                var url = "do.php";
                var params = "what=remove_terapia&IDterapia=" + IDterapia;
                http.open("POST", url, true);

                http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                http.onreadystatechange = function() {
                    if(http.readyState == 4 && http.status == 200) {
                        window.location.href = 'tabella_terapie.php?id=' + codicefiscale;
                    }
                }
                http.send(params);
        }
    </script>
</body>

<?php
    
    function viewDesktop($param)
    {
        echo '<table class="table table-striped table-bordered text-center table-responsive">';
        echo '<thead></thead>';
        echo '<tbody>';
        $week=["null","Lunedì","Martedì","Mercoledì","Giovedì","Venerdì","Sabato","Domenica"];

        while ($r2 = $param->fetch_assoc())
        {
        $rowspan=1;
        $posologia = array();
        $temp = array();
        $orari = array();
        $posologia_length = 0;
        $orari_length = 0;

        if($r2["TipoPosologia"]=="Settimanale")
        {
            $posologia=explode(",", $r2["Posologia"]);
            $posologia_length=count($posologia);
            $orari=explode(",", $r2["Orari"]);
            $orari_length = count($orari);
            $temp[0] = "null";

            for($i=0;$i<$posologia_length;$i++)
            {
                if($posologia[$i]!=0)
                {
                    $temp[$rowspan]=$week[$i];
                    $rowspan++;
                }
            }

            for($i=1;$i<$rowspan;$i++)
            {
                echo'<tr>';
                if($i==1)
                {
                    echo "<td rowspan=\"".($rowspan-1)."\">".$r2["NomeFarmaco"]."</td>";    
                }
                    echo "<td>". $temp[$i]."</td>";

                    echo "<td>";
                    echo $r2["Quantita"];
                        if($r2["Quantita"] == 1)
                            echo " dose</td>";
                        else
                            echo " dosi</td>";

                    echo "<td>"; 

                    for($j=1;$j<$orari_length;$j++)
                    {
                        if($orari[$j]!="")
                            echo $orari[$j]."<br>";
                    }

                    echo "</td>";
                    echo '<td>Dal '.date("d-m-Y", strtotime($r2["Inizio"])).'<br> Al '.date("d-m-Y", strtotime($r2["Fine"])).'</td>';

                if($i==1)
                {
                    printf ("<td rowspan=\"".($rowspan-1)."\"><a href=\"form_terapia.php?edit=%s&id=%s\"><span class=\"glyphicon glyphicon glyphicon-pencil\"></span></a></td>", $r2["IDterapia"], $r2["CodFiscale"]);
                    printf ("<td rowspan=\"".($rowspan-1)."\"><a href=\"#\" onclick=\"deleteModalData('%s','%s','%s')\" data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"glyphicon glyphicon glyphicon-trash\"></span></a></td>", $r2["IDterapia"], $r2["CodFiscale"], $r2["NomeFarmaco"]);
                }

                echo "</tr>";
            }
                

            }else
            {
                $posologia=explode(",", $r2["Posologia"]);
                $posologia_length=count($posologia);
                $orari=explode(",", $r2["Orari"]);
                $orari_length = count($orari);
                $temp[0] = "null";
                $countDays = 0; $j = 0; //per verificare se il giorno selezionato nel mensile è unico

                echo'<tr>';
                echo "<td>".$r2["NomeFarmaco"]."</td>"; 

                for($i=0;$i<$posologia_length;$i++){
                    if($posologia[$i]!=0)
                        $countDays++; //quanti giorni mensili son stati selezionati
                }

                if($countDays == 1){ //se è unico il giorno selezionato nella terapia mensile
                        echo "<td> Il giorno ";                   
                }else{  //se invece ci sono più giorni nella terapia mensile
                    echo "<td> I giorni ";
                }

                for($i=0;$i<$posologia_length;$i++){
                    if($posologia[$i]!=0)
                    {
                        echo $posologia[$i];
                        $j++;

                        if($j < $countDays)
                            echo ", ";
                    }
                }

                echo " del Mese</td>";
                
                echo "<td>";
                echo $r2["Quantita"];
                    if($r2["Quantita"] == 1)
                        echo " dose</td>";
                    else
                        echo " dosi</td>";

                echo "<td>"; 
                    for($j=1;$j<$orari_length;$j++){
                            if($orari[$j]!="")
                                echo $orari[$j]."<br>";
                        }
                    echo "</td>";
                    echo '<td>Dal '.date("d-m-Y", strtotime($r2["Inizio"])).' <br> Al '.date("d-m-Y", strtotime($r2["Fine"])).'</td>';
                    printf ("<td rowspan=\"".($rowspan-1)."\"><a href=\"form_terapia.php?edit=%s&id=%s\"><span class=\"glyphicon glyphicon glyphicon-pencil\"></span></a></td>", $r2["IDterapia"], $r2["CodFiscale"]);
                    printf ("<td rowspan=\"".($rowspan-1)."\"><a href=\"#\" onclick=\"deleteModalData('%s','%s','%s')\" data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"glyphicon glyphicon glyphicon-trash\"></span></a></td>", $r2["IDterapia"], $r2["CodFiscale"], $r2["NomeFarmaco"]);
                echo "</tr>";
            }  

        }
            echo '</tbody>';
        echo'</table>';
    }

    function viewMobile($param)
    {
        $week=["null","Lunedì","Martedì","Mercoledì","Giovedì","Venerdì","Sabato","Domenica"];

        while ($r2 = $param->fetch_assoc())
        {
        echo '<table class="table table-striped table-bordered text-center table-responsive">';
            echo '<thead></thead>';
            echo '<tbody>';

            $rowspan=1;
            $posologia = array();
            $temp = array();
            $orari = array();
            $posologia_length = 0;
            $orari_lengthn = 0;

            if($r2["TipoPosologia"]=="Settimanale"){
                $posologia=explode(",", $r2["Posologia"]);
                $posologia_length=count($posologia);
                $orari=explode(",", $r2["Orari"]);
                $orari_length = count($orari);
                $temp[0] = "null";

                for($i=0;$i<$posologia_length;$i++){
                    if($posologia[$i]!=0){
                        $temp[$rowspan]=$week[$i];
                        $rowspan++;
                    }
                }

                echo '<tr>';
                    echo '<td colspan="2">'.$r2["NomeFarmaco"].'</td>';
                    printf ("<td><a href=\"form_terapia.php?edit=%s&id=%s\"><span class=\"glyphicon glyphicon glyphicon-pencil\"></span></a></td>", $r2["IDterapia"], $r2["CodFiscale"]);
                    printf ("<td><a href=\"#\" onclick=\"deleteModalData('%s','%s','%s')\" data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"glyphicon glyphicon glyphicon-trash\"></span></a></td>", $r2["IDterapia"], $r2["CodFiscale"], $r2["NomeFarmaco"]);
                echo '</tr>';

                for($i=1;$i<$rowspan;$i++){
                    echo'<tr>';
                        echo "<td>". $temp[$i]."</td>";
                        
                        echo "<td>";
                        echo $r2["Quantita"];
                        if($r2["Quantita"] == 1)
                            echo " dose</td>";
                        else
                            echo " dosi</td>";

                        echo '<td colspan="3">'; 

                        for($j=1;$j<$orari_length;$j++){
                            if($orari[$j]!="")
                                echo $orari[$j]."<br>";
                        }

                        echo "</td>";
                    echo '</tr>';
                }
                        
                echo '<tr>';
                    echo '<td colspan="4">Dal '.date("d-m-Y", strtotime($r2["Inizio"])).' al '.date("d-m-Y", strtotime($r2["Fine"])).'</td>';
                echo '</tr>';
            }else{
                $posologia=explode(",", $r2["Posologia"]);
                $posologia_length=count($posologia);
                $orari=explode(",", $r2["Orari"]);
                $orari_length = count($orari);
                $temp[0] = "null";
                $countDaysM = 0; $jM = 0;


                echo '<tr>';
                    echo '<td colspan="2">'.$r2["NomeFarmaco"].'</td>';
                    printf ("<td><a href=\"form_terapia.php?edit=%s&id=%s\"><span class=\"glyphicon glyphicon glyphicon-pencil\"></span></a></td>", $r2["IDterapia"], $r2["CodFiscale"]);
                    printf ("<td><a href=\"#\" onclick=\"deleteModalData('%s','%s','%s')\" data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"glyphicon glyphicon glyphicon-trash\"></span></a></td>", $r2["IDterapia"], $r2["CodFiscale"], $r2["NomeFarmaco"]);
                echo '</tr>';

                for($i=0;$i<$posologia_length;$i++){
                    if($posologia[$i]!=0)
                        $countDaysM++; //quanti giorni mensili son stati selezionati
                }

                for($i=1;$i<=$rowspan;$i++){
                    echo'<tr>';

                        if($countDaysM == 1) // se solo un giorno è presente nella terapia mensile
                            echo "<td> Il giorno ";
                        else
                            echo "<td> I giorni ";

                        for($i=0;$i<$posologia_length;$i++){
                            if($posologia[$i]!=0){
                                echo $posologia[$i];
                                $jM++;
                                if($jM < $countDaysM)
                                    echo ", ";
                            }
                        }
                        echo " del Mese</td>";
                        
                        echo "<td>";
                        echo $r2["Quantita"];
                        if($r2["Quantita"] == 1)
                            echo " dose</td>";
                        else
                            echo " dosi</td>";

                        echo '<td colspan="2">'; 

                            for($j=1;$j<$orari_length;$j++){
                                if($orari[$j]!="")
                                    echo $orari[$j]."<br>";
                            }

                        echo "</td>";
                    echo '</tr>';
                }
                        
                echo '<tr>';
                    echo '<td colspan="4">Dal '.date("d-m-Y", strtotime($r2["Inizio"])).' al '.date("d-m-Y", strtotime($r2["Fine"])).'</td>';
                echo '</tr>';
            }  
            echo '</tbody>';
        echo'</table>';
        }
    }

?>        