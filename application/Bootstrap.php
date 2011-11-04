<?php
/**
 * Bootstrap.php
 * Main application bootstrap file
 * @author Lucian Hontau
 * @copyright Lucian Hontau
 */

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public function __construct($application)
    {
        parent::__construct($application);
    }

	/**
	 * Sets the doctype
	 */
    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
    }

	/**
	 * Grabs the config and puts it into the registry
	 */
    public function run()
    {
        // make the config available to the registry
		Zend_Registry::set('config', new Zend_Config($this->getOptions()));
        parent::run();
    }

	/**
	 * Inits the autoloader to load modules with the Bills namespace
	 * 
	 * @return Zend_Application_Module_Autoloader
	 */
    protected function _initAutoload()
    {
        $loader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Bills',
            'basePath'  => APPLICATION_PATH));

        return $loader;
    }

	/**
	 * Inits the view and view helpers
	 * 
	 * @return Zend_View
	 */
    protected function _initView()
    {
        $view = new Zend_View();
        $view->addHelperPath(APPLICATION_PATH . '/views/helpers');

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        return $view;
    }

}
