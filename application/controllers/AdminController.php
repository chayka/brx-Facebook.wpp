<?php

class brx_Facebook_AdminController extends Zend_Controller_Action{
    
    public function init(){
        wp_enqueue_style('backbone-brx-optionsForm');
        wp_enqueue_script('backbone-brx-optionsForm');
    }
    
    public function fbOptionsAction(){
        
    }
}