<?php

namespace asb\yii2\modules\contactform_3_170124\helpers;

use asb\yii2\modules\contactform_3_170124\BackendModule;
use asb\yii2\modules\contactform_3_170124\models\UserModel;

use Yii;
use yii\helpers\Html;

/**
 * @author ASB <ab2014box@gmail.com>
 */
trait UserFormatterTrait
{
    protected static $_users = [];
    protected static $_tc;

    public function asUsername($id)
    {
        $tc = BackendModule::$tc;
        if (empty($tc)) $tc = CommonModule::$tc;

        $userIdentity = Yii::$app->user->identity;
        $usernameField = UserModel::$usernameField;
        if (empty(self::$_users[$id])) {
            self::$_users[$id] = $userIdentity->findIdentity($id);
        }
        $value = empty(self::$_users[$id]->$usernameField)
               ? ( empty(self::$_users[$id]) ? Yii::t($tc, '(anonymous)') : Yii::t($tc, 'user #') . $id )
               : self::$_users[$id]->username;
        return Html::encode($value);
    }
}
