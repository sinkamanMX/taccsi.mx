<?php
class admin_ValidationController extends My_Controller_Action
{
	protected $_clase = 'mvalidation';
	
    public function init()
    {
    	try{
    		$this->view->layout()->setLayout('admin_layout');
    		
    		$sessions = new My_Controller_Auth();
			$perfiles = new My_Model_Perfiles();
			$fc 	  = Zend_controller_front::getInstance();
	        if($sessions->validateSession()){
		        $this->_dataUser   = $sessions->getContentSession();   	
		        $this->_baseUrl    = $fc->getBaseUrl();	
			}else{
				$this->_redirect("/login/main/index");
			}    		
			$this->view->dataUser   = $this->_dataUser;			
			$this->view->modules    = $perfiles->getModules($this->_dataUser['TIPO_USUARIO']);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
			$this->view->BaseUrl    = $this->_baseUrl;
						
			$this->_dataIn 					= $this->_request->getParams();
			$this->_dataIn['userCreate']	= $this->_dataUser['ID_USUARIO'];
			$this->_dataIn['dataIdEmpresa'] = $this->_dataUser['ID_EMPRESA'];
	    	if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];				
			}
			
			if(isset($this->_dataIn['catId'])){
				$this->_idUpdate = $this->_dataIn['catId'];				
			}			
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
    public function indexAction(){
    	try{
			$classObject = new My_Model_Taxistas();
			$this->view->datatTable = $classObject->getDataToValidate();
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    } 

    public function getinfoAction(){
    	try{
    		$dataInfo = Array();
    		$cFunctions	= new My_Controller_Functions();
    		$classObject= new My_Model_Taxistas();
    		$cTaxis		= new My_Model_Taxis();    		
    		$cColores   = new My_Model_Colores();
    		$cModelos	= new My_Model_Modelos();
    		$cMarcas	= new My_Model_Marcas();
    		$cEmpresas  = new My_Model_Empresas();
    		
    		$aColores	= $cColores->getCbo();
    		$aMarcas	= $cMarcas->getCbo();
    		$aEmpresas  = $cEmpresas->getCbo();
    		$aModelos   = Array();
    		$sColores	= '';
    		$sMarcas	= '';
    		$sModelos   = '';    		
    		$sEstatus	= '';
    		$sPropio	= '';    		

    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
    	    	$sPropio	= $dataInfo['TAXI_PROPIO'];   
    	    	
    	    	$dataTaxi   = $cTaxis->getDataByDriver($dataInfo['ID_USUARIO']);
    	    	
	    		$sColores	= $dataTaxi['ID_COLOR'];
	    		$sMarcas	= $dataTaxi['ID_MARCA'];
	    		$sModelos   = $dataTaxi['ID_MODELO'];   

				$aModelos	= $cModelos->getCbo($sMarcas);
				$this->view->aModelos   = $cFunctions->selectDb($aModelos,$sModelos);

				if($this->_dataOp=="opRegister"){
					$iEmpresa = (isset($this->_dataIn['inputEmpresa']) && $this->_dataIn['inputEmpresa']!="") ? $this->_dataIn['inputEmpresa']: -2;
					$iActivo  = 1;
					//Se debe de asignar a la empresa seleccionada
					$nameTaxista = $dataInfo['NOMBRE']." ".$dataInfo['APATERNO']." ".$dataInfo['AMATERNO'];
					if($this->_dataIn['iRespuesta']==1 && $this->_dataIn['inputEmpresa']!=""){
						if($this->_dataIn['inputEmpresa']=="1"){
							$bodymail   = '<h3>Bienvenido</h3>'.
										  'Estimado '.$nameTaxista.': Se reviso la solicitud enviada para formar parte de Taccsi.com<br/>'.
										  'La informaci&oacute;n que se recibio fue validada por uno de nuestros ejecutivos<br/>'.
										  'Desde este momento puede comenzar a recibir viajes en su taccsi';															
						}elseif($this->_dataIn['inputEmpresa']!="1"){
							$dataEmpresa= $cEmpresas->getData($this->_dataIn['inputEmpresa']); 
							$bodymail   = '<h3>Bienvenido</h3>'.
										  'Estimado '.$nameTaxista.': Se reviso la solicitud enviada para formar parte de Taccsi.com<br/>'.
										  'La informaci&oacute;n que se recibio fue validada por uno de nuestros ejecutivos<br/>'.
										  'Desde este momento puede comenzar a recibir viajes en su taccsi<br/>'.
										  'Para comenzar a utilizar la aplicaci&oacute;n, es necesario que inicie sesi&oacute;n en su dispositivo con los siguientes datos:'.
										  '<table><tr><td>Clave Empresa: </td><td>'.$dataEmpresa['CODIGO_EMPRESA'].'</td></tr>'.
										  '<tr><td>Usuario: </td><td>'.$dataInfo['NICKNAME'].'</td></tr>'.
										  '<tr><td>Contrase&ntilde;a: </td><td>'.$dataInfo['PASSWORD_TEXT'].'</td></tr>'.									  
										  '</table><br/>';
						}
					}else{
						$bodymail   = '<h3>Estimado '.$nameTaxista.':</h3>'.
									  'Se reviso la solicitud enviada para formar parte de Taccsi.com<br/>'.
									  'La informaci&oacute;n que se recibio fue validada por uno de nuestros ejecutivos<br/>'.
									  'Este proceso no fue aprobado, por lo que recomendamos comunicarse para obtener mas informaci&oacute;n.';
						$iActivo    = 0;
					}	
									
					//Se debe de marcar como empresa -2 y enviar un mensaje de rechazado.
					$update = $classObject->setActivation($iEmpresa,$this->_idUpdate,$iActivo); 
					if($update){
						$cTaxis->updateEmpresa($iEmpresa,$dataTaxi['ID_TAXI']);	
						$aMailer    = Array(
							'emailTo' 	=> $dataInfo['NICKNAME'],
							'nameTo' 	=> $nameTaxista,
							'subjectTo' => ('Taccsi - Respuesta de Solicitud'),
							'bodyTo' 	=> $bodymail,
						);	
					 	$enviar = $cFunctions->sendMailSmtp($aMailer);
					   	$this->_resultOp= 'okRegister';	
					   	$this->_redirect("/admin/validation/index");											
					}else{
						$this->_aErrors['update'] = 1;
					}
				}
			}
			
			
			$this->view->aMarcas	= $cFunctions->selectDb($aMarcas,$sMarcas);
			$this->view->aColores	= $cFunctions->selectDb($aColores,$sColores);   
			$this->view->taxpropio  = $cFunctions->cboOptions($sPropio);
			$this->view->aEmpresas	= $cFunctions->selectDb($aEmpresas);			
			$this->view->data 		= $dataInfo;
			$this->view->dataTaxi   = $dataTaxi;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;		
			$this->view->resultOp   = $this->_resultOp;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 
    }    
}