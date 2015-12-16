<?php  	

	$conexion = new mysqli('localhost','dba','t3cnod8A!','TACCSI') or die("Some error occurred during connection " . mysqli_error($conexion));

	$idEmpresa  = (isset($_GET['iEmpresa'])) ? $_GET['iEmpresa'] : 6;
	$idTipoTaxi = (isset($_GET['tipoTaxi'])) ? $_GET['tipoTaxi'] : 3;
	$iLatitud   = (isset($_GET['iLatitud'])) ? $_GET['iLatitud'] : 19.4915680000;
	$iLongitud	= (isset($_GET['iLongitud']))? $_GET['iLongitud']: -99.2556070000;


  	$sql = "SELECT T.ID_TARIFA, T.DESCRIPCION,T.USA_TAXIMETRO, T.BANDERAZO,T.COSTO_KILOMETRO, T.COSTO_MINUTOS, T.HORARIO_INICIO,T.HORARIO_FIN, T.KM_FUERA_HORARIO, T.MIN_FUERA_HORARIO, T.KM_FUERA_ZONA, T.MIN_FUERA_ZONA, T.COSTO_FUERA_HORARIO, T.TIPO_TARIFA,
				astext(CENTROID(MAP_OBJECT)) AS N_POINT, DISTANCIA($iLatitud,$iLongitud,X(CENTROID(MAP_OBJECT)),Y(CENTROID(MAP_OBJECT))) AS DISTANCIA,
				Z.DESCRIPCION AS N_ZONA, Z.COSTO AS COSTO_ZONA, Z.COSTO_ACUMULABLE
			FROM ADMIN_ZONAS Z
			INNER JOIN ADMIN_TARIFAS T ON Z.ID_TARIFA = T.ID_TARIFA
			WHERE Z.ID_TARIFA 
			IN (
				SELECT ID_TARIFA
				FROM ADMIN_TARIFAS 
				WHERE ID_CLASE    = $idTipoTaxi
				  AND (ID_EMPRESA = $idEmpresa OR TIPO_TARIFA = 0)
				)
			AND CONTAINS(MAP_OBJECT, geomfromtext('Point($iLatitud $iLongitud)'))
			ORDER BY DISTANCIA ASC"; 
  	$query = mysqli_query($conexion, $sql);	
	while($result = mysqli_fetch_array($query)){
		echo "<br/>";
		var_dump($result);
		echo "<br/>";
	}