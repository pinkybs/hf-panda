<?php

define('ADMIN_USERNAME','ipanda'); 	// Admin Username
define('ADMIN_PASSWORD','ipandaadmin');  	// Admin Password

class ChristmasController extends Zend_Controller_Action
{
	protected $uid;

	    public function init()
    {
    	$controller = $this->getFrontController();
        $controller->unregisterPlugin('Zend_Controller_Plugin_ErrorHandler');
        $controller->setParam('noViewRenderer', true);
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
           $_SERVER['PHP_AUTH_USER'] != ADMIN_USERNAME ||$_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD) {
			Header("WWW-Authenticate: Basic realm=\"Project Name\"");
			Header("HTTP/1.0 401 Unauthorized");

			echo <<<EOB
				<html><body>
				<h1>拒绝访问</h1>
				<big>账号和密码错误</big>
				</body></html>
EOB;
			exit;
		}
    }

	public function configAction(){
	    $config = Hapyfish2_Ipanda_Event_Cache_Christmas::getConfig();
	    if (empty($config)) {
        	$this->view->config = false;
        } else {
        	$this->view->config = $config;
        }
    	$this->render();
	}
	public function updateAction(){
		$config = Hapyfish2_Ipanda_Event_Cache_Christmas::getConfig();
		$config['RateMin'] = $this->_request->getParam('RateMin');
		$config['RateMax'] = $this->_request->getParam('RateMax');
		$config['Hit'] = $this->_request->getParam('Hit');
		$config['HitInterval'] = $this->_request->getParam('HitInterval');
		$config['Max'] = $this->_request->getParam('Max');
		$key = 'ipanda:e:c:config';
		$cache = Hapyfish2_Cache_Factory::getBasicMC('mc_0');
		$cache->set($key, $config);
		$this->_redirect("christmas/config");
	}
}