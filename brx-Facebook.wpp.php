<?php
/*
  Plugin Name: brx-Facebook
  Plugin URI: http://github.com/chayka/brx-Facebook.git
  Description: Empty plugin.
  Version: 1.0
  Author: Boris Mossounov
  Author URI: http://facebook.com/mossounov
  License: GPL2
 */
require_once 'application/helpers/WidgetHelper_brx_Facebook.php';
require_once 'application/helpers/HtmlHelper_brx_Facebook.php';
require_once 'application/helpers/OptionHelper_brx_Facebook.php';
class brx_Facebook extends WpPlugin{
    
    protected static $instance = null;
    
    const POST_TYPE_DUMMY = 'dummy';
    const TAXONOMY_DUMMY_TAG = 'dummy-tag';
    
    public static function baseUrl(){
        echo BRX_FACEBOOK_URL;
    }
    
    public static function init() {
        
        NlsHelper::load('brx_Facebook');

        self::$instance = $plugin = new brx_Facebook(__FILE__, array(
//            'dummy',
//            'dummies',
        ));
        
//        $plugin->addSupport_CustomPermalinks();
        $plugin->addSupport_Metaboxes();
        $plugin->addSupport_ConsolePages();

        //  Uncomment if you need processing on post create, update, delete    
        //  $plugin->addSupport_PostProcessing();

        
//        ZF_Query::forbidRoute('/^blog\b/');
    }

    public static function getInstance() {
        return self::$instance;
    }

    public function registerCustomPostTypes() {
    }

    
    public function enableSearch($query){
        return $query;
    }
    
    public function registerTaxonomies(){

    }

    

    public function postPermalink($permalink, $post, $leavename = false){
        return $permalink;
    }
    
    public function termLink($link, $term, $taxonomy){
        return $link;
    }

    public function registerNavMenus(){

    }
    
    public function customizeNavMenuItems($items, $args){
        return $items;
    }
    
    public function registerMetaBoxes() {
        
        $this->addMetaBox('seo_params',
            NlsHelper::_( 'SEO Params'),
            '/metabox/seo-params',
            'normal',
            'high');
    }
    
    public function savePost($postId, $post){

    }
    
    public function registerResources($minimize = false){
//        $this->registerStyle('brx.Facebook.Dummy.view', 'brx.Facebook.Dummy.view.less', array());
//        $this->registerScript('brx.Facebook.Dummy.view', 'brx.Facebook.Dummy.view.js', array('backbone-brx', 'brx.Facebook.Dummy.nls'));
//        NlsHelper::registerScriptNls('brx.Facebook.Dummy.nls', 'brx.Facebook.Dummy.view.js');
    }
    
    public function registerActions(){

    }
    
    public function registerFilters(){

    }
    
    public function registerConsolePages() {
        $this->addConsolePage('Theme Options', 'Theme Options', 'update_core', 'brx-facebook-plugin-options', '/admin/plugin-options');

    }

    public function registerSidebars() {

    }

    public static function blockStyles($block = true) {
        
    }
}

add_action('init', array('brx_Facebook', 'init'));
