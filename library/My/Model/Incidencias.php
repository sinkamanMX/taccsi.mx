<?php

class My_Model_Incidencias extends My_Db_Table
{
    protected $_schema 	= 'taccsi';
	protected $_name 	= 'ATN_INCIDENCIAS';
	protected $_primary = 'ID_SERVICIO';
	
	public function getIncidencias($idViaje){
		$result= Array();
    	$sql ="SELECT S.ID_SERVICIO,S.ID_VIAJE,S.FECHA_REGISTRO, I.NOMBRE_SERVICIO, E.DESCRIPCION AS ESTATUS
				FROM ".$this->_name." S
				INNER JOIN ATN_TIPO_INCIDENCIA I ON S.ID_TIPO_INCIDENCIA = I.ID_TIPO_INCIDENCIA
				INNER JOIN ATN_INCIDENCIAS_ESTATUS E  ON S.ID_INCIDENCIAS_ESTATUS = E.ID_INCIDENCIAS_ESTATUS
				WHERE ID_VIAJE = ".$idViaje;			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query;			
		}
		        
		return $result;		
	}
}