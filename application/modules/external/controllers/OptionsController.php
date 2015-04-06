<?php

class external_OptionsController extends My_Controller_Action
{	
    public function init()
    {
		$this->view->layout()->setLayout('layout_contact');
		
		$sessions = new My_Controller_AuthContact();
		if($sessions->validateSession()){
	        $this->view->dataUser   = $sessions->getContentSession();
		}
    }
    
    public function indexAction()
    {
		$this->view->layout()->setLayout('layout_options');    	
		try{
			
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
}