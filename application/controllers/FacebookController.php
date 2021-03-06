<?php

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

class brx_Facebook_FacebookController extends Zend_Controller_Action{
    public function init(){
        
    }
    
    public function loginAction() {
        $accessToken = InputHelper::getParam('accessToken');
        $expiresIn = InputHelper::getParam('expiresIn');
        $signedRequest = InputHelper::getParam('signedRequest');
        $userID = InputHelper::getParam('userID');

        FacebookSession::setDefaultApplication(FacebookHelper::getAppID(), FacebookHelper::getAppSecret());
        $session = new FacebookSession($accessToken);

        // Get the GraphUser object for the current user:
        $me = null;
        try {
            $me = (new FacebookRequest(
                    $session, 'GET', '/me'
                    ))->execute()->getGraphObject(GraphUser::className());
        } catch (FacebookRequestException $e) {
            // The Graph API returned an error
            JsonHelper::respondException($e);
        } catch (\Exception $e) {
            // Some other error occurred
            JsonHelper::respondException($e);
        }
        $user = null;
        if ($me && $me->getId() == $userID) {
            $email = $me->getProperty('email');
            if($email){
//                echo "[email captured $email] ";
                $user = UserModel::selectByEmail($email);
                if($user){
//                    echo "[user found] ";
//                    Util::print_r($user);
                    $user->updateMeta('fb_user_id', $userID);
                }
            }
            if(!$user){
//                echo "[fetching by id $userID] ";
                $user = UserModel::query()
                        ->metaQuery('fb_user_id', $userID)
                        ->selectOne();
//                Util::print_r($user);
            }
            if (!$user) {
//                echo "[no user found, creating new one]";
                $user = new UserModel();
                $wpUserId = $user->setLogin('fb' . $userID)
                        ->setEmail($email?$email:$userID . "@facebook.com")
                        ->setDisplayName($me->getName())
                        ->setFirstName($me->getFirstName())
                        ->setLastName($me->getLastName())
                        ->setNicename(ZF_Core::slug(strtolower(join('.', array($me->getFirstName(), $me->getLastName())))))
                        ->setPassword(wp_generate_password(12, false))
                        ->insert();
//                Util::print_r($user);
                if ($wpUserId) {
//                    echo "[user created] ";
                    $user->updateMeta('fb_user_id', $userID);
                    $user->updateMeta('source', 'facebook');
                    $user = UserModel::selectById($user->getId());
//                    Util::print_r($user);
                }
            }
//            echo "[authenticating with user] ";
//            Util::print_r($user);
            $secure_cookie = is_ssl();
            wp_set_auth_cookie($user->getId(), false, $secure_cookie);
            do_action('wp_login', $user->getLogin(), $user->getWpUser());
            JsonHelper::respond($user);
        }

        JsonHelper::respondError('', 'authentication_failed');
        
    }
    
    public function channelAction(){
        $locale = InputHelper::getParam('locale', 'en_US');
        $cache_expire = 60*60*24*365;
        header("Pragma: public");
        header("Cache-Control: max-age=".$cache_expire);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$cache_expire) . ' GMT');
        die('<script src="//connect.facebook.net/'.$locale.'/all.js"></script>');
    }
}
