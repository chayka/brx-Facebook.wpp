<?php

class OptionHelper_brx_Facebook {
    public static function getOption($option, $default='', $reload = false){
        $key = 'brx_Facebook.'.$option;
        return get_option($key, $default, !$reload);
    }
    
    public static function setOption($option, $value){
        $key = 'brx_Facebook.'.$option;
        return update_option($key, $value);
    }
    
    public static function getSiteOption($option, $default='', $reload = false){
        $key = 'brx_Facebook.'.$option;
        return get_site_option($key, $default, !$reload);
    }
    
    public static function setSiteOption($option, $value){
        $key = 'brx_Facebook.'.$option;
        return update_site_option($key, $value);
    }
    
}