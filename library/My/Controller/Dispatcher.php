<?php
class My_Controller_Dispatcher extends Zend_Controller_Dispatcher
{
    public function getAction($request)
    {
        $action = $request->getActionName();
        if (empty($action)) {
            $action = $this->getDefaultAction();
            $request->setActionName($action);
            $action = $this->formatActionName($action);
        } else {
            $controller = $this->getController();
            $action     = $this->formatActionName($action);
            if (!method_exists($controller, $action)) {
                $action = $this->getDefaultAction();
                $request->setActionName($action);
                $action = $this->formatActionName($action);
            }
        }
 
        return $action;
    }
}
?>