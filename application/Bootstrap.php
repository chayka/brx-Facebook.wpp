<?php

class brx_Facebook_Bootstrap extends WpPluginBootstrap{
    
    const MODULE = 'brx_Facebook';

    public function run(){
        parent::run();
//        WpHelper::getInstance();
        
    }
    
    public function getModuleName() {
        return self::MODULE;
    }
    
    public function setupRouting(){
        $router = parent::setupRouting();
        $router->addRoute('post', new Zend_Controller_Router_Route('entry/:id/:post_name', array('controller' => 'post', 'action'=>'entry', 'module'=>self::MODULE), array('id'=>'\d+')));
        $router->addRoute('dummy', new Zend_Controller_Router_Route('dummy/:id/:post_name', array('controller' => 'dummy', 'action'=>'entry', 'module'=>self::MODULE), array('id'=>'\d+')));
        $router->addRoute('dummies', new Zend_Controller_Router_Route('dummies/:page', array('controller' => 'dummy', 'action'=>'list', 'module'=>self::MODULE, 'page' => 1), array('page'=>'\d+')));

        //  Uncomment this if you need custom 404 page 
        //  and customize ErrorController->notFound404Action() and it's view
        //  $router->addRoute('not-found-404', new Zend_Controller_Router_Route('not-found-404', array('controller' => 'error', 'action'=>'not-found-404', 'module'=>self::MODULE), array()));        
        
    }


}

