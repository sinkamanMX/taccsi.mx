<?php
class admin_CarsController extends My_Controller_Action
{
	protected $_clase = 'mcars';
	
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
			$classObject = new My_Model_Taxis();
			$this->view->datatTable = $classObject->getDataTables($this->_dataUser['ID_EMPRESA']);
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    } 
	
    public function getinfoAction(){
		try{
    		$dataInfo   = Array();
    		$cFunctions	= new My_Controller_Functions();
    		$classObject= new My_Model_Taxis();
    		$cColores   = new My_Model_Colores();
    		$cModelos	= new My_Model_Modelos();
    		$cMarcas	= new My_Model_Marcas();
    		$cEstatus	= new My_Model_Estatustaxi();
    		$cTarifas	= new My_Model_Tarifas();
    		$cEstados	= new My_Model_Spestados();
    		$cTypes		= new My_Model_Clases();
    		
    		$aColores	= $cColores->getCbo();
    		$aMarcas	= $cMarcas->getCbo();
    		$aEstatus	= $cEstatus->getCbo();
    		$aTipoTaxi  = $classObject->getCboTipoTaxi();
    		$aEstados   = $cEstados->getCbo();
    		$aClases	= $cTypes->getCbo();
    		$aTarifas	= Array();
    		$aModelos   = Array();
    		$sColores	= '';
    		$sMarcas	= '';
    		$sModelos   = '';
    		$sEstatus	= '';
    		$sTipoTaxi  = '';
    		$sTarifas	= '';    		
			$sNameImage = '';    	
			$sEstado    = '';	
			$sClase		= '';
			    		    		
    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getData($this->_idUpdate,$this->_dataUser['ID_EMPRESA']);
    	    	if(count($dataInfo)>0){
    	    		$sColores	= $dataInfo['ID_COLOR'];    	    	
	    	    	$sModelos	= $dataInfo['ID_MODELO'];
	    	    	$sMarcas	= $dataInfo['ID_MARCA'];
	    	    	$sEstatus	= $dataInfo['ID_ESTATUS_TAXI'];
	    	    	$sTipoTaxi  = $dataInfo['ID_TIPO_TAXI'];
	    			$sTarifas	= $dataInfo['ID_TARIFA'];
	    			$sEstado	= $dataInfo['ID_ESTADO'];
	    			$sClase		= $dataInfo['ID_CLASE'];
					$aModelos	= $cModelos->getCbo($sMarcas);
					if($sClase!="" && $sEstado!=""){
						$aTarifas	= $cTarifas->getCboSelect($this->_dataUser['ID_EMPRESA'], $sEstado,$sClase);	
					}
													  
					$this->view->aModelos   = $cFunctions->selectDb($aModelos,$sModelos);
    	    	}else{
    	    		$this->_redirect("/admin/cars/index");
    	    	}
			}
			
			if($this->_dataOp=='new'){			
				if($_FILES['imageProfile']['name']!=""){
		            $allowedExts = array("jpeg", "jpg", "png");
					$temp = explode(".", $_FILES["imageProfile"]["name"]);
					$extension = end($temp);

					$newNameimage = Date("Ymdhis").".".$extension;
					if ((($_FILES["imageProfile"]["type"] == "image/jpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/jpg")
						|| ($_FILES["imageProfile"]["type"] == "image/pjpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/x-png")
						|| ($_FILES["imageProfile"]["type"] == "image/png"))
						&& ($_FILES["imageProfile"]["size"] < 10485760)
						&& in_array($extension, $allowedExts)) {
								
						  if ($_FILES["imageProfile"]["error"] > 0) {
						  		$this->_aErrors['errorImage'] = 1;
						  }else{
							  $sNameImage  = $newNameimage;

							  $upload = move_uploaded_file($_FILES["imageProfile"]["tmp_name"],  "images/taxis/" . $newNameimage);
							  if(!$upload){
								$this->_aErrors['errorImage'] = 1;  	
							  }else{
							  	$nameDelete = "images/taxis/".$dataInfo['IMAGEN'];							  	
							  	if(file_exists($nameDelete)){
							  		unlink($nameDelete);	
							  	}							  	
							  	$this->_dataIn['nameImagen'] = $sNameImage;
							  }			
						  }
					}else {
					  $this->_aErrors['errorImage'] = 1;
					}
	            }
	            
	            $this->_dataIn['sImagenTcir']   = $this->validateFields('imageTcirculacion');
	            $this->_dataIn['sImagenTcir2']  = $this->validateFields('imageTbcirculacion');
	            $this->_dataIn['sImagenFact']   = $this->validateFields('imageFactura');
	            $this->_dataIn['sImagenPol']    = $this->validateFields('imagepoliza');
	            if(count($this->_aErrors)==0){
	            	$this->_dataIn['nameImagen'] = ($sNameImage!="") ? $this->_dataIn['nameImagen'] : '';
	            	$insert = $classObject->insertRow($this->_dataIn);
			 		if($insert['status']){
			 			$this->_idUpdate = $insert['id'];				 		
		    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
		    	    	$sColores	= $dataInfo['ID_COLOR'];    	    	
		    	    	$sModelos	= $dataInfo['ID_MODELO'];
		    	    	$sMarcas	= $dataInfo['ID_MARCA'];
		    	    	$sEstatus	= $dataInfo['ID_ESTATUS_TAXI'];
		    	    	$sTipoTaxi  = $dataInfo['ID_TIPO_TAXI'];
		    	    	$sTarifas	= $dataInfo['ID_TARIFA'];
		    	    	$sEstado	= $dataInfo['ID_ESTADO'];
		    	    	$sClase		= $dataInfo['ID_CLASE'];
			 			if($sClase!="" && $sEstado!=""){
							$aTarifas	= $cTarifas->getCboSelect($this->_dataUser['ID_EMPRESA'], $sEstado,$sClase);	
						}
						$aModelos	= $cModelos->getCbo($sMarcas);
						$this->view->aModelos   = $cFunctions->selectDb($aModelos,$sModelos);

				 		if($dataInfo['IMAGEN_TCIRCULACION']   !="" && $dataInfo['IMAGEN_TCIRCULACION_2']!="" 
					            && $dataInfo['IMAGEN_FACTURA']!="" && $dataInfo['IMAGEN_POLIZA']){
							
								$cHtmlMail = new My_Controller_Htmlmailing();							
								$cHtmlMail->documentsCompleteCar($dataInfo);
						}
											
				 		$this->_resultOp = 'okRegister';
					}else{
						$this->errors['status'] = 'no-insert';
					}	            	
	            }
	
			}else if($this->_dataOp=='update'){	  
				$sNameImage = '';
				
				if($_FILES['imageProfile']['name']!=""){
		            $allowedExts = array("jpeg", "jpg", "png");
					$temp = explode(".", $_FILES["imageProfile"]["name"]);
					$extension = end($temp);
					
					$newNameimage = Date("Ymdhis").".".$extension;
					if ((($_FILES["imageProfile"]["type"] == "image/jpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/jpg")
						|| ($_FILES["imageProfile"]["type"] == "image/pjpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/x-png")
						|| ($_FILES["imageProfile"]["type"] == "image/png"))
						&& ($_FILES["imageProfile"]["size"] < 10485760)
						&& in_array($extension, $allowedExts)) {
								
						  if ($_FILES["imageProfile"]["error"] > 0) {
						  		$this->_aErrors['errorImage'] = 1;
						  }else{
							  $sNameImage  = $newNameimage;

							  $upload = move_uploaded_file($_FILES["imageProfile"]["tmp_name"], "images/taxis/" . $newNameimage);
							  if(!$upload){
								$this->_aErrors['errorImage'] = 1;  	
							  }else{
							  	$nameDelete = "images/taxis/".$dataInfo['IMAGEN'];
							  	unlink($nameDelete);
							  	$this->_dataIn['nameImagen'] = $sNameImage;
							  }							  
						  }
					}else {
					  $this->_aErrors['errorImage'] = 1;
					}
	            }
	            
	            $this->_dataIn['sImagenTcir']   = $this->validateFields('imageTcirculacion');
	            $this->_dataIn['sImagenTcir2']  = $this->validateFields('imageTbcirculacion');
	            $this->_dataIn['sImagenFact']   = $this->validateFields('imageFactura');
	            $this->_dataIn['sImagenPol']    = $this->validateFields('imagepoliza');	   	            
	            
				if(count($this->_aErrors)==0){	   
					$this->_dataIn['nameImagen'] = ($sNameImage!="") ? $this->_dataIn['nameImagen'] : '';         	
	            	$updated = $classObject->updateRow($this->_dataIn);
			 		if($updated['status']){		 		
		    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
		    	    	$sColores	= $dataInfo['ID_COLOR'];    	    	
		    	    	$sModelos	= $dataInfo['ID_MODELO'];
		    	    	$sMarcas	= $dataInfo['ID_MARCA'];
		    	    	$sEstatus	= $dataInfo['ID_ESTATUS_TAXI'];
		    	    	$sTipoTaxi  = $dataInfo['ID_TIPO_TAXI'];
		    	    	$sTarifas	= $dataInfo['ID_TARIFA'];
		    	    	$sEstado	= $dataInfo['ID_ESTADO'];
		    	    	$sClase		= $dataInfo['ID_CLASE'];
		    	    	if($sClase!="" && $sEstado!=""){
							$aTarifas	= $cTarifas->getCboSelect($this->_dataUser['ID_EMPRESA'], $sEstado,$sClase);	
						}
		    	    	
						$aModelos	= $cModelos->getCbo($sMarcas);
						$this->view->aModelos   = $cFunctions->selectDb($aModelos,$sModelos);

			 			if($dataInfo['IMAGEN_TCIRCULACION']   !="" && $dataInfo['IMAGEN_TCIRCULACION_2']!="" 
					            && $dataInfo['IMAGEN_FACTURA']!="" && $dataInfo['IMAGEN_POLIZA']){
							
								$cHtmlMail = new My_Controller_Htmlmailing();							
								$cHtmlMail->documentsCompleteCar($dataInfo);
						}
												
				 		$this->_resultOp = 'okRegister';
					}else{
						$this->errors['status'] = 'no-insert';
					}	            	
	            }
			}else if($this->_dataOp=='delete'){
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				$answer = Array('answer' => 'no-data');
				    
				$this->_dataIn['idEmpresa'] = 1;
				$delete = $classObject->deleteRow($this->_dataIn);
				if($delete){
					$answer = Array('answer' => 'deleted'); 
				}	
	
		        echo Zend_Json::encode($answer);
		        die();			
			}			
			
			if(count($this->_aErrors)>0 && $this->_dataOp!=""){
				$dataInfo['PLACAS']	= $this->_dataIn['inputPlacas'];
				$dataInfo['ECO']	= $this->_dataIn['inputEco'];
				$dataInfo['NOMBRE_CHOFER']	= $this->_dataIn['inputChofer'];
				$dataInfo['ANIO']	= $this->_dataIn['inputAno'];
				
    	    	$sColores	= $this->_dataIn['inputColor'];		    	
    	    	$sModelos	= $this->_dataIn['inputModelo'];		
    	    	$sMarcas	= $this->_dataIn['inputMarca'];
    	    	$sEstatus	= $this->_dataIn['inputEstatus'];
    	    	$sTipoTaxi  = $this->_dataIn['inputSize'];
    	    	
				$aModelos	= $cModelos->getCbo($sMarcas);
				$this->view->aModelos   = $cFunctions->selectDb($aModelos,$sModelos);
			}
			
			$this->view->aEstados	= $cFunctions->selectDb($aEstados,$sEstado);
			$this->view->aStatus  	= $cFunctions->selectDb($aEstatus,$sEstatus);
			$this->view->aMarcas	= $cFunctions->selectDb($aMarcas,$sMarcas);
			$this->view->aColores	= $cFunctions->selectDb($aColores,$sColores);
			$this->view->aTIpo		= $cFunctions->selectDb($aTipoTaxi,$sTipoTaxi);
			$this->view->aTarifas	= $cFunctions->selectDb($aTarifas,$sTarifas);
			$this->view->aTypes		= $cFunctions->selectDb($aClases,$sClase);   
			$this->view->data 		= $dataInfo; 
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;			
		}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 			
    }
    
    public function gettarifasAction(){
    	try{
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();    
			    	
	    	$result = 'no-info';
			$this->dataIn = $this->_request->getParams();
			$functions = new My_Controller_Functions();				
			$validateNumbers = new Zend_Validate_Digits();
			$validateAlpha   = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));
					
			if($validateNumbers->isValid($this->dataIn['catId']) && $validateNumbers->isValid($this->dataIn['classObject'])){
				$cTarifas	= new My_Model_Tarifas();
				$cboValues  = $cTarifas->getCboSelect($this->_dataUser['ID_EMPRESA'],$this->dataIn['catId'],$this->dataIn['classObject']);
				$result      = $functions->selectDb($cboValues);
			}
			
			echo $result;
		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function validateFields($inputName){
    	$targetFolder 	 = '/var/www/vhosts/taccsi.com/htdocs/public/images/documentacion';
    	//$targetFolder 	 ='/Users/itecno2/Documents/workspace/taccsi.mx/public/images/documentacion';
    	$sResult = '';
		if(@$_FILES[$inputName]['name']!=""){
			$tempFile   = $_FILES[$inputName]['tmp_name'];				
			$targetFile = $targetFolder.'/'.$_FILES[$inputName]['name'];
			
			$fileTypes = array('png','jpg','jpeg','pdf');
			$fileParts = pathinfo($_FILES[$inputName]['name']);
								
			if (in_array($fileParts['extension'],$fileTypes)) {
				$nFile			= $inputName."_".date("YmdHis").'.'.$fileParts['extension'];
				$nameFinalFile  = $targetFolder.'/'.$nFile;				
				if(move_uploaded_file($tempFile,$nameFinalFile)){
					$sResult = $nFile;											
				}else{
					$this->_aErrors['errorImage'] = 1;
				}
			}else{
				$this->_aErrors['errorImage'] = 1;
			}
		}
		return $sResult;    	
    }
}