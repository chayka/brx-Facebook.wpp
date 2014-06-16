<?php

class WidgetHelper_brx_Facebook extends WidgetHelper{
    
    public static function renderWidget($data, $tpl, $js, $css = null) {
        parent::addScriptPath(BRX_FACEBOOK_APPLICATION_PATH.'/views/scripts');
        return parent::renderWidget($data, $tpl, $js, $css);
    }
    
    public static function renderDummy($user){
        return self::renderWidget(array('user'=>$user), 'widgets/brx.Facebook.Dummy.view.phtml', 'brx.Facebook.Dummy.view');
    }

    public static function renderDummyForm($user){
        return self::renderWidget(array('user'=>$user), 'widgets/brx.Facebook.DummyForm.view.phtml', 'brx.Facebook.DummyForm.view');
    }
}
