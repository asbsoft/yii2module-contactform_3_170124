<?php

namespace asb\yii2\modules\contactform_3_170124\models;

use asb\yii2\modules\contactform_3_170124\CommonModule;
use asb\yii2\modules\contactform_3_170124\BackendModule;
use asb\yii2\modules\contactform_3_170124\FrontendModule;
use asb\yii2\modules\contactform_3_170124\controllers\MainController;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%contactform}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $body
 * @property string $ip
 * @property string $browser
 * @property string $create_at
 */
class Contactform extends ActiveRecord
{
    const SCENARIO_FRONTFORM = 'FRONTFORM';
    
    public $captchaActionUid = '';

    public $controllerId = 'main';

    public $verifyCode;

    public $tc;

    public $adminEmail;

    public $userName;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contactform}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->adminEmail = Yii::$app->params['adminEmail'];

        $module = FrontendModule::getInstance();
        if (empty($module)) $module = BackendModule::getInstance();
        if (empty($module)) $module = CommonModule::getInstance(); //?! Find only first module of this class among system modules.
                                                                   // therefore only one such module or it's successor can add to system
        if (!empty($module)) {
            $moduleUid = $module->uniqueId;
            $this->tc = $module->tcModule;
            UserModel::$tc = $this->tc;
            $this->captchaActionUid = $moduleUid . '/' . $this->controllerId . '/' . MainController::$captchaActionId;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'subject'], 'string', 'max' => 255],
            [['body'], 'string'],
            [['name', 'email', 'body'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'string', 'on' => self::SCENARIO_FRONTFORM,
                'min' => MainController::$captchaMinLength,
                'max' => MainController::$captchaMaxLength
            ],
            ['verifyCode', 'captcha', 'on' => self::SCENARIO_FRONTFORM,
                'skipOnEmpty' => false,
                'caseSensitive' => false,
                'captchaAction' => $this->captchaActionUid,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'ip'        => 'IP',
            'name'      => Yii::t($this->tc, 'Name'),
            'user_id'   => Yii::t($this->tc, 'User'),
            'email'     => Yii::t($this->tc, 'Email'),
            'subject'   => Yii::t($this->tc, 'Subject'),
            'body'      => Yii::t($this->tc, 'Text'),
            'browser'   => Yii::t($this->tc, 'Browser'),
            'create_at' => Yii::t($this->tc, 'Sent at'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findOne($condition)
    {
        $result = static::findByCondition($condition)->one();
        $result->userName = UserModel::username($result->user_id);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->scenario == self::SCENARIO_FRONTFORM) {
            $this->ip = Yii::$app->request->getUserIP();
            $this->user_id = Yii::$app->user->getId(); // or null
            $this->browser = Yii::$app->request->userAgent;
        }
        $result = parent::save($runValidation, $attributeNames);

        $this->sendEmail($this->adminEmail);

        return $result;
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function sendEmail($email)
    {
        Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
        return true;
    }

}
