<?php

class brx_Facebook_MetaboxController extends Zend_Controller_Action{
    
    public function init(){
    }
    
    public function FbParamsAction(){
        global $post;
        
        $zfPost = PostModel::unpackDbRecord($post);
        wp_nonce_field( 'fb_params', 'fb_params_nonce' );

        $meta = array();
        
        $this->view->fb_title = $meta['fb_title'] = $zfPost->getMeta('fb_title');
        $this->view->fb_description = $meta['fb_description'] = $zfPost->getMeta('fb_description');

        $this->view->meta = $meta;
        
        $this->view->postId = $post->ID;
        $this->view->post = $zfPost;
    }
    
    
}
