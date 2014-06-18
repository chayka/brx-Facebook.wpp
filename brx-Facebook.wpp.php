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
require_once 'application/helpers/FacebookHelper.php';
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
            'facebook'
        ));
        
        UserModel::addJsonMetaField('fb_user_id');
        CommentModel::addJsonMetaField('fb_user_id');
        self::registerFbAutoloader();
        
        $plugin->addSupport_Metaboxes();
        $plugin->addSupport_ConsolePages();

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
        
        $this->addMetaBox('fb_params',
            NlsHelper::_( 'Facebook Params'),
            '/metabox/fb-params/',
            'normal',
            'high');
    }
    
    public function savePost($postId, $post){

    }
    
    public function registerResources($minimize = false){
        $this->registerStyle('brx.Facebook.Auth.view', 'brx.Facebook.Auth.view.less', array());
        $this->registerScript('brx.Facebook.Auth.view', 'brx.Facebook.Auth.view.js', array('backbone-brx', 'brx.Facebook.Auth.nls'));
        NlsHelper::registerScriptNls('brx.Facebook.Auth.nls', 'brx.Facebook.Auth.view.js');
    }
    
    public function registerActions(){
        $this->addAction('wp_head', array('HtmlHelper_brx_Facebook', 'renderMeta'));
    }
    
    
    
    public function registerFilters(){
        $this->addFilter('get_avatar', 'getFbAvatar', 10, 3);
        $this->addFilter('CommentModel.created', 'markCommentWithFbUserId');
        $this->addFilter('pre_comment_approved', 'approveFbUserComment', 10, 2);

    }
    
    public function registerConsolePages() {
        $this->addConsolePage('Facebook Options', 'Facebook Options', 'update_core', 'brx-facebook-plugin-options', '/admin/fb-options');

    }

    public function registerSidebars() {

    }

    public static function blockStyles($block = true) {
        
    }
    
    public static function getFbAvatar($avatar, $id_or_email, $size = 96){
        if(!$id_or_email){
            return $avatar;
        }
        $user = null;
        if(is_object($id_or_email)){
            $user = UserModel::unpackDbRecord($id_or_email);
        }else{
            $user = is_email($id_or_email)?
                    UserModel::selectByEmail($id_or_email):
                    UserModel::selectById($id_or_email);
        }
        if($user){
            $metaFbUseId = $user->getMeta('fb_user_id');
            if($metaFbUseId){
                if(!intval($size)){
                    $size = 96;
                }
                $avatarUrl = sprintf('//graph.facebook.com/%s/picture?type=square&width=%d&height=%d', $metaFbUseId, (int)$size, $size);
                return preg_replace("%src='[^']*'%", "src='$avatarUrl'", $avatar);
            }
        }else{
//            return preg_replace("%alt='[^']*'%", "alt='user not found'", $avatar);
        }
        
        return $avatar;
    }
    /**
     * 
     * @param CommentModel $comment
     * @return CommentModel
     */
    public function markCommentWithFbUserId($comment){
        if($comment->getUserId()){
            $user = UserModel::selectById($comment->getUserId());
            if($user && $user->getMeta('fb_user_id')){
                $comment->updateMeta('fb_user_id', $user->getMeta('fb_user_id'));
            }
        }
        return $comment;        
    }
    
    public function approveFbUserComment($approved, $rawComment){
//        $comment = CommentModel::unpackDbRecord($rawComment);
//        Util::print_r($comment);
        $userId = Util::getItem($rawComment, 'user_id');
        if($userId){
            $user = UserModel::selectById($userId);
            if($user && $user->getMeta('fb_user_id')){
//                echo ' approved ';
                $approved = true;
            }
        }
        return $approved;
//        'pre_comment_approved'
    }
    
    public static function fbPhpApiAutoloader($class){
        if(preg_match('%^Facebook%', $class) && 'FacebookHelper'!==$class){
            $parts = explode('\\', $class);
            require_once 'fb-php-api'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.  join(DIRECTORY_SEPARATOR, $parts).'.php';
        }
    }
    
    public static function registerFbAutoloader(){
        spl_autoload_register(array('brx_Facebook', 'fbPhpApiAutoloader'));
    }
    
}

add_action('init', array('brx_Facebook', 'init'));
