<?php

class FacebookHelper{
    protected static $post = null;
    protected static $title = null;
    protected static $description = null;
    protected static $image = null;
    protected static $type = 'website';
    protected static $url = null;
    
    /**
     * 
     * @param PostModel $post
     */
    public static function setPost($post){
        self::$post = $post;
    }
    
    public static function setTitle($title){
        self::$title = $title;
    }
    
    /**
     * 
     * @param PostModel $post
     * @return String
     */
    public static function getTitle($post = null){
        if(!$post){
            $post = self::$post;
        }
        if($post){
            if($post->getMeta('fb_title')){
                return $post->getMeta('fb_title');
            }
//            if($post->getMeta('seo_title')){
//                return $post->getMeta('seo_title');
//            }
            return self::$post->getTitle();
        }
        if(self::$title){
            return self::$title;
        }
        if(HtmlHelper::getHeadTitle()){
            return HtmlHelper::getHeadTitle();
        }
        return OptionHelper_brx_Facebook::getOption('default_title', get_bloginfo( 'name' ));
    }
    
    public static function setDescription($description){
        self::$description = $description;
    }
    
    /**
     * 
     * @param PostModel $post
     * @return type
     */
    public static function getDescription($post = null){
        if(!$post){
            $post = self::$post;
        }
        if($post){
            if($post->getMeta('fb_description')){
                return $post->getMeta('description');
            }
            if($post->getMeta('description')){
                return $post->getMeta('description');
            }
            if($post->getMeta('seo_description')){
                return $post->getMeta('seo_description');
            }
            return $post->getExcerpt();
        }
        if(self::$description){
            return self::$description;
        }
        if(HtmlHelper::getMetaDescription()){
            return HtmlHelper::getMetaDescription();
        }
        return OptionHelper_brx_Facebook::getOption('default_description', get_bloginfo( 'description' ));
    }
    
    public static function setImage($image){
        self::$image = $image;
    }
    
    /**
     * 
     * @param PostModel $post
     * @return String
     */
    public static function getImage($post = null){
        if(!$post){
            $post = self::$post;
        }
//        if(!$post){
//            $post = HtmlHelper::g;
//        }
        if($post){
            if($post->getThumbnailId()){
                $thumbnail = $post->getThumbnailData_Full();
                return Util::getItem($thumbnail, 'url');
            }
        }
        if(self::$image){
            return self::$image;
        }
//        if(HtmlHelper::getMetaDescription()){
//            return HtmlHelper::getMetaDescription();
//        }
        return OptionHelper_brx_Facebook::getOption('default_image');
    }
    
    public static function setType($type){
        self::$type = $type;
    }
    
    public static function getType(){
        return self::$type;
    }
    
    public static function setUrl($url){
        self::$url = $url;
    }
    
    /**
     * 
     * @param PostModel $post
     * @return type
     */
    public static function getUrl($post = null){
        if(!$post){
            $post = self::$post;
        }
        if($post){
            return 'http://'.$_SERVER['SERVER_NAME'].$post->getHref();
        }
        if(self::$url){
            return self::$url;
        }
        return 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }
    
    public static function getAppID(){
        return OptionHelper_brx_Facebook::getOption('app_id');
    }
    
    public static function getAppSecret(){
        return OptionHelper_brx_Facebook::getOption('app_secret');
    }
    
    public static function getAppAdmins(){
        return OptionHelper_brx_Facebook::getOption('admins');
    }
    
}

