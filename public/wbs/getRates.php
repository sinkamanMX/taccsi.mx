<?php 
	require 'cFunctions.php';
?>
<html>
<head>
	<title>Obtener tarifas</title>
</head>
<body>
	<form role="form" method="GET" action="" id="FormData" >
	    <input type="hidden" id="optReg"      name="optReg" value="search" />
	    <label>Id taccsi</label>
	    <input type="text"  name="inputTaccsi" value="8" />
	    <h4>Origen:</h4>
	    <label>Latitud</label>
	    <input type="text"  name="iOrigenLat" value="19.5723790000" />
	    <label>Longitud</label>
	    <input type="text"  name="iOrigenLon" value="-99.2123680000" />
		<h4>Destino:</h4>
	    <label>Latitud</label>
	    <input type="text"  name="iDestLat" value="19.5081346000" />
	    <label>Longitud</label>
	    <input type="text"  name="idestlon" value="-99.2342093000" />	    
	    <br/><br/>
	    <input type="submit" value="enviar">
	</form>

	<?php if(@$_GET['optReg']=='search'):?>
		<table border="1">
			<tr>
				<th>Banderazo</th>
				<th>Se Cobra </th>
				<th>Costo </th>
				<th>Destino </th>
				<th>Distancia Aprox. (kms) </th>
				<th>Costo Total Aprox. </th>
			</tr>
			<tr>
				<td><?php echo "$".round($banderazo,2);?></td>
				<td><?php echo "cada ".$cobrarCada."kms o ".$cobrarCadaMins." mins."  ;?></td>
				<td><?php echo "$".round($aCosto,2);?></td>
				<td><?php echo $aDestino;?></td>
				<td><?php echo round($cotrolDistancia,2);?></td>
				<td><?php echo "$".round($costoRecorrido,2);?></td>
			<tr/>
		</table>
	<?php endif;?>
</body>
</html>