<?php

class UrlHelper_brx_Facebook extends UrlHelper{

    public static function dummies($page = 1){
        return self::getRouter()->assemble(array(
                'page' => $page,
            ), 'dummies', true);
    }
    
    public static function userProfile($user){
        if(!($user instanceof UserModel)){
            $user = UserModel::unpackDbRecord($user);
        }
        return $user->getProfileLink();
    }
    
}