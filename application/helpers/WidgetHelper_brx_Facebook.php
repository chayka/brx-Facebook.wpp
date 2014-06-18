<?php

class WidgetHelper_brx_Facebook extends WidgetHelper{
    
    public static function renderWidget($data, $tpl, $js, $css = null) {
        parent::addScriptPath(BRX_FACEBOOK_APPLICATION_PATH.'/views/scripts');
        return parent::renderWidget($data, $tpl, $js, $css);
    }
    
    public static function renderAuth(){
        return self::renderWidget(array(), 'widgets/brx.Facebook.Auth.view.phtml', 'brx.Facebook.Auth.view');
    }
}
