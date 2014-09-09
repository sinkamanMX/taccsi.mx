<?php
class admin_JsonController extends My_Controller_Action
{		
    public function init()
    {    	
    	$sessions = new My_Controller_Auth();
        if(!$sessions->validateSession()){
            $this->_redirect("/admin/login/index");	
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();   		
    }    
    
    public function getclientesAction(){	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		    
        $result = "";
		$data   = $this->_request->getParams();
				
		$nameFilter	= $data['namefilter'];
		$appFilter	= $data['appfilter'];
		$apmFilter	= $data['apmfilter'];
		$telFilter 	= $data['telfilter'];		
		$clientes    = new My_Model_Clientes();		
		$dataClients = $clientes->getClients($data);

		if(count($dataClients)>0){
			
			 foreach($dataClients as $key => $items){
			 	$result[] = $items;
	    	}			
		}
		
		echo Zend_Json::encode( $result = array('aaData'=>$result ) );
		       	
    }    
}