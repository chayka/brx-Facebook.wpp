<?php

/**
 * This helper lets render some specific html pieces outside of MVC logic
 */

class HtmlHelper_brx_Facebook{
    
    /**
     * Get zend view
     * 
     * @return \Zend_View
     */
    public static function getView(){
        $view = new Zend_View();
        $view->setScriptPath(BRX_FACEBOOK_PATH.'application/views/scripts');
        
        return $view;
    }
    
    /**
     * Render zend view with supplied vars
     * 
     * @param string $path
     * @param array $vars
     * @return string
     */
    public static function renderView($path, $vars = array(), $output = true){
        $view = self::getView();
        foreach($vars as $key=>$val){
            $view->assign($key, $val);
        }
        $res = $view->render($path);
        if($output){
            echo $res;
        }
        return $res;
    }
    
    public static function renderBreadCrumbs($crumbs){
        return self::renderView('post/breadcrumbs.phtml', array('crumbs'=>$crumbs), true);
    }
    
    public static function renderPostAttachments($post, $output = true){
        $metaAttachments = $post->getMeta('attachments');
        if($metaAttachments){
            $attachments = array();
            $metaAttachments = json_decode($metaAttachments);
            foreach($metaAttachments->attachments as $attachment){
                $postAttachment = PostModel::query()
                        ->postId($attachment->id)
                        ->postType_Attachment()
                        ->selectOne();
                if(isset($attachment->fields) && !empty($attachment->fields->title)){
                    $postAttachment->setTitle($attachment->fields->title);
                }
                if(isset($attachment->fields) && !empty($attachment->fields->caption)){
                    $postAttachment->setExcerpt($attachment->fields->caption);
                }
                $attachments[]=$postAttachment;
            }
            return self::renderView('post/attachments.phtml', array(
                'post'=>$post,
                'attachments'=>$attachments,
            ), $output);
        }
        return '';
    }
    
    public static function renderMeta(){
        self::renderView('facebook/meta.phtml');
    }
}

