<?php
class external_ReportsController extends My_Controller_Action
{
	protected $_clase = 'mreports';
	public $realPath='/var/www/vhosts/taccsi.com/htdocs/public';	
	//public $realPath='/Users/itecno2/Documents/workspace/taccsi.mx/public';
	
    public function init()
    {
    	try{
    	    $this->view->layout()->setLayout('admin_layout');
			$sessions = new My_Controller_AuthContact();
			$perfiles = new My_Model_Perfiles();
						
    	    if($sessions->validateSession()){
		        $this->_dataUser   = $sessions->getContentSession();   		
			}else{
				$this->_redirect('/external/login/index');		
			}			
			
			$this->_dataIn 			= $this->_request->getParams();
			$this->view->dataUser   = $this->_dataUser;
			$this->view->modules    = $perfiles->getModules(5);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
			$this->view->bUserContact = true;	

			$this->validateNumbers = new Zend_Validate_Digits();
					
			if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];
				
				if($this->_dataOp=='update'){
					$this->_dataOp = $this->_dataIn['optReg'];
	
					$this->validateAlpha   = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));				
				}
			}
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
    public function indexAction(){
        try{
    		$cFunctions     = new My_Controller_Functions();
			
    		$cUsuarios      = new My_Model_ServUsuarios();
    		$idUser 		= $this->_dataUser['ID_SRV_USUARIO'];
    		$aTravels 		= Array();
    		$aTravels 		= $cUsuarios->getTravelsByUser($idUser);

    		$this->view->data		= $this->_dataIn;
    		$this->view->aTravels  	= $aTravels;   
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function serviceinfoAction(){
    	try{
    		$cTaxis				= new My_Model_Taxis();
    		$cCliente   		= new My_Model_Clientes();  
    		$cViajes			= new My_Model_Viajes();
    		$cIncidencias		= new My_Model_Incidencias();
			$aDataViaje 		= Array();
    		$aDataClient		= Array();
    		$aDataIncidencias 	= Array();
    		$rDataOpr			= false;
    			
    		if(isset($this->_dataIn['strViaje'])){
    			$idViaje	= $this->_dataIn['strViaje'];
    			$aDataViaje = $cViajes->infoViaje($idViaje);
    			$aDataClient= $cCliente->getDataClient($aDataViaje['ID_CLIENTE']);  
				$aDataIncidencias = $cIncidencias->getIncidencias($idViaje);
    			$idTaxista 	= $aDataViaje['ID_TAXISTA'];
    			
    			if($this->_dataOp=='renew'){
    				if($idTaxista==NULL){
    					$delete = $cViajes->deleteAssign($idViaje);    					
    					if($delete){	
    						$updated = $cViajes->resetViaje($idViaje);
    						if($updated['status']){
								$aDataViaje['inputLatOrigen'] = $aDataViaje['ORIGEN_LATITUD'];
	    						$aDataViaje['inputLonOrigen'] = $aDataViaje['ORIGEN_LONGITUD'];
								$aTaxis	 = $cTaxis->getTaxiService($aDataViaje);						
								$this->addTaxis($aTaxis, $idViaje);
								$this->_redirect('/callcenter/services/serviceinfo?strViaje='.$idViaje);    						
	    						$rDataOpr = true;	
    						}    						
    					}
    				}
    			}
    		}else{
    			$this->_redirect('/callcenter/services/index');
    		}    		
    		
    		$this->view->data			  = $this->_dataIn;    		
    		$this->view->dataOpr	  	  = $rDataOpr;		
			$this->view->aDataViaje   	  = $aDataViaje; 
			$this->view->aDataCliente 	  = $aDataClient;
			$this->view->aDataIncidencias = $aDataIncidencias;
    		$this->view->idViaje		  = $this->_dataIn['strViaje'];
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	    	
    }   

    public function exportAction(){
    	try{			
    		$cTaxis				= new My_Model_Taxis();
    		$cCliente   		= new My_Model_Clientes();  
    		$cViajes			= new My_Model_Viajes();
    		$cIncidencias		= new My_Model_Incidencias();
			$aDataViaje 		= Array();
    		$aDataClient		= Array();
    		$aDataIncidencias 	= Array();
    		$rDataOpr			= false;
    			
    		if(isset($this->_dataIn['strViaje'])){
    			$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				
    			$idViaje	= $this->_dataIn['strViaje'];
    			$aDataViaje = $cViajes->infoViaje($idViaje);
    			$aDataClient= $cCliente->getDataClient($aDataViaje['ID_CLIENTE']);  
				$aDataIncidencias = $cIncidencias->getIncidencias($idViaje);
    			$idTaxista 	= $aDataViaje['ID_TAXISTA'];

				/*$dataInfo    = $classObject->getDataExport($this->dataIn['travelID']);						
				$aRecorrido  = $classObject->getRecorrido($this->dataIn['travelID']);	*/			
				
				$nameClient = $this->_dataUser['N_EMPRESA']; 
				$dateCreate = date("d-m-Y H:i");
				$createdBy	= $this->_dataUser['NICKNAME'];     			
    				/** PHPExcel */ 
					require_once 'PHPExcel.php';		
											
					if (!PHPExcel_Settings::setPdfRenderer(
							PHPExcel_Settings::PDF_RENDERER_DOMPDF,
							$this->realPath.'/PHPExcel/Classes/dompdf'
					)) {
						die(
							'NOTICE: Please set the $rendererName and asdads$rendererLibraryPath values' .
							'<br />' .
							'at the top of this script as appropriate for your directory structure'
						);
					}
					
					// PHPExcel_Writer_Excel2007 
					include 'PHPExcel/Writer/Excel2007.php';			
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->getProperties()->setCreator("UDA")
											 ->setLastModifiedBy("UDA")
											 ->setTitle("Office 2007 XLSX")
											 ->setSubject("Office 2007 XLSX")
											 ->setDescription("Reporte del Viaje")
											 ->setKeywords("office 2007 openxml php")
											 ->setCategory("Reporte del Viaje");
					$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);	
																 
					$sHeaderBig    		= new PHPExcel_Style();
					$sHeaderBigblack    = new PHPExcel_Style();
					$sHeaderBig->applyFromArray(array(
						'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => 'FFFFFF')
				        ),
				        'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => 'A4A4A4'),
					        'size'  => 20,
					        'name'  => 'Calibri'
					    ),
						  'borders' => array(
						    'allborders' => array(
						      'style' => PHPExcel_Style_Border::BORDER_NONE
						    )
						  )				        
					));
					
					$sHeaderBigblack->applyFromArray(array(
						'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => 'FFFFFF')
				        ),
				        'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => '424242'),
					        'size'  => 20,
					        'name'  => 'Calibri'
					    ),
						  'borders' => array(
						    'allborders' => array(
						      'style' => PHPExcel_Style_Border::BORDER_NONE
						    )
						  )				        
					));					
					
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('Logo');
					$objDrawing->setDescription('Logo');
					$objDrawing->setPath($this->realPath.'/images/assets/logo-admin.png');
					$objDrawing->setWidth(100);
					$objDrawing->setHeight(90);
					//$objDrawing->setOffsetX(10);
					$objDrawing->setCoordinates('D1');					
					$objPHPExcel->getActiveSheet()->getRowDimension('D1')->setRowHeight(70);										
					$objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

					$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(70);					
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'TACCSI');
					//$objPHPExcel->getActiveSheet()->mergeCells('C3:D3');
					$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($sHeaderBig, 'C3');					
				
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', $dateCreate);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', 'Monto ($)');
					$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($sHeaderBigblack, 'C5');					

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "$ ". round($aDataViaje['MONTO'],2));
					$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($sHeaderBigblack, 'E5');

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C7', 'Fecha Viaje');
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E7', $aDataViaje['FECHA_VIAJE']);	
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C8', 'No. personas');
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E8', $aDataViaje['NO_PASAJEROS']." ");					
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C9', 'Forma de Pago');
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E9', "".$aDataViaje['FPAGO']);					

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C10', 'Origen');
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E10', "".$aDataViaje['ORIGEN']);					

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C11', 'Destino');
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E11', "".$aDataViaje['DESTINO']);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C13', 'Taxista');
					$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($sHeaderBig, 'C13');	

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C15', 'Nombre');
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E15', $aDataViaje['TAXISTA']);					

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C16', 'Taxi');					
					$aDataTaxi = explode("<br/>", $aDataViaje['TAXI']);
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E16', @$aDataTaxi[0]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E17', @$aDataTaxi[1]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E18', @$aDataTaxi[2]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E19', @$aDataTaxi[3]);		

					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
					
    
					$objPHPExcel->setActiveSheetIndex(0)->setShowGridLines(false);
					$objPHPExcel->setActiveSheetIndex(0)->setPrintGridLines(false);						
					
					$objPHPExcel->setActiveSheetIndex(0);
					$filename  = "Viaje_".$aDataViaje['ID_VIAJES']."_".date("Y_m_d").".pdf";

					header('Content-Type: application/pdf');
					header('Content-Disposition: attachment;filename="'.$filename.'"');
					header('Cache-Control: max-age=0');									
					
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
					$objWriter->save('php://output');					
					/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="'.$filename.'"');
					header('Cache-Control: max-age=0');			
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					$objWriter->save('php://output');    */			
    		}else{
    			$this->_redirect('/reports/travelsrep/index');
    		} 		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	      	
    }    
}