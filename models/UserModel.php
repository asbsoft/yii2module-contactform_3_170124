<?php

namespace asb\yii2\modules\contactform_3_170124\models;

use asb\yii2\modules\contactform_3_170124\BackendModule;

use Yii;
use yii\db\ActiveRecord;

class UserModel
{
    public static $idField = 'id';
    public static $usernameField = 'username';

    //public static $tc = 'contactform/module';
    public static $tc;
    public static function prepareTransCat()
    {
        $module = BackendModule::getInstance();
        static::$tc = $module->tcModule;
    }

    protected static $_users;
    public static function users()
    {
        if (empty(static::$_users)) {
            $userIdentity = Yii::$app->user->identity;
            if ($userIdentity instanceof ActiveRecord) {
                static::$_users = $userIdentity::find()->asArray()->all();
            } elseif (method_exists ($userIdentity, 'users')) {
                static::$_users = $userIdentity::users();
            }
        }
        return static::$_users;
    }
    
    public static function username($id)
    {
        $users = static::users();
        foreach ($users as $i => $user) {
            if ($user[static::$idField] == $id) {
                $username = $users[$i][static::$usernameField];
                break;
            }
        }
        static::prepareTransCat();
        if (empty($username)) {
            if(intval($id) > 0) {
                $username = Yii::t(static::$tc, 'user #' . $id);
            } else {
                $username = Yii::t(static::$tc, 'anonymous');
            }
        }
        return $username;
    }
    
    public static function usernameList()
    {
        static::prepareTransCat();
        $list = [];
        $users = static::users();
        if (isset($users) && is_array($users)) {
            foreach ($users as $user) {
                $username = empty($user[static::$usernameField])
                    ? Yii::t(static::$tc, 'user #' . $user[static::$idField])
                    : $user[static::$usernameField];
                $list[$user[static::$idField]] = $username;
            }
        }
        return $list;
    }
}
