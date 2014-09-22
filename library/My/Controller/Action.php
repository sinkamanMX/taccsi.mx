<?php
/**
 * Archivo de definici�n de clase 
 * 
 * @package library.My.Controller
 * @author andres
 */

/**
 * Definici�n de clase de controlador gen�rico
 *
 * @package library.My.Controller
 * @author andres
 */
class My_Controller_Action extends Zend_Controller_Action
{
	/**
	 * Identidad
	 * 
	 * @var mixed
	 */
	protected $_idUpdate = Array();
		
	/**
	 * Identidad
	 * 
	 * @var mixed
	 */
	protected $_dataUser= Array();
	
    /**
     * 
     * @var array 
     */
    protected $_menuCurrent;
    
    /**
     * 
     * @var array 
     */
    protected $_dataIn;  

    /**
     * 
     * @var array 
     */
    protected $_dataOp;    
    /**
     * 
     * @var array 
     */
    protected $_resultOp;  

	/**
	 * Identidad
	 * 
	 * @var mixed
	 */
	protected $_aErrors= Array();    
	
	/**
	 * Identidad
	 * 
	 * @var mixed
	 */
	protected $_baseUrl= Array();  	
    
   /**
    * Inicializa el contexto, el formato de respuesta a un action
    *
    * @return void
    */
    public function init() {		

    }
 

    /**
     * M�todo llamado previo a llamada de controlador
     *
     * @return void
     */
    public function preDispatch()
    {
  
            
    }

    /**
     * Procedimiento posterior a llamado de controlador
     *
     * @return void
     */
    public function postDispatch(){
		
    }
}
