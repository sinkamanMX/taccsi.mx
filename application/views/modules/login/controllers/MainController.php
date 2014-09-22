<?php

class login_MainController extends My_Controller_Action
{
    public function init()
    {
		$this->view->layout()->setLayout('admin_layout');
    }

    public function indexAction()
    {
        $sessions = new My_Controller_Auth();
        if($sessions->validateSession()){
            $this->_redirect("/admin/main/index");		
		}    	
		    	
    	$this->view->header = true;
    	$this->view->layout()->setLayout('admin_layout'); 
		try{
          
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function loginAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();    
                
        $answer = Array('answer' => 'no-data');
		$data = $this->_request->getParams();
        if(isset($data['usuario']) && isset($data['contrasena'])){
            $usuarios = new My_Model_Usuarios();
			$validate = $usuarios->validateUser($data); //recogemos los valores y mandamos            
			if($validate){
				 $dataUser = $usuarios->getDataUser($validate['ID_USUARIO']);
			     $sessions = new My_Controller_Auth();
                 $sessions->setContentSession($dataUser);
                 $sessions->startSession();
			     $answer = Array('answer' => 'logged');			    
			}else{ 
			    $answer = Array('answer' => 'no-perm'); 
			}
        }else{
            $answer = Array('answer' => 'problem');	
        }
        echo Zend_Json::encode($answer);                
    }    
    
    public function logoutAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();        	
		$mysession= new Zend_Session_Namespace('taccSession');
		$mysession->unsetAll();
		
		Zend_Session::namespaceUnset('taccSession');
		Zend_Session::destroy();
		$this->_redirect('/login/main/index');
    }
}    