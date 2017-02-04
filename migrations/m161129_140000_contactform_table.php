<?php

use asb\yii2\modules\contactform_3_170124\Module;

use asb\yii2\modules\contactform_3_170124\models\Contactform as Model;

use yii\db\Schema;
use yii\db\Migration;
use yii\db\Expression;

/**
 * @author ASB <ab2014box@gmail.com>
 */
class m161129_140000_contactform_table extends Migration
{
    protected $tableName;
    protected $idxNamePrefix;

    public function init()
    {
        parent::init();

        Yii::setAlias('@asb/yii2/modules', '@vendor/asb/yii2modules');

        $this->tableName = Model::tableName();
        $this->idxNamePrefix = 'idx-' . $this->tableName;
    }

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'        => $this->primaryKey(),
            'name'      => $this->string(255)->notNull(),
            'email'     => $this->string(255)->notNull(),
            'subject'   => $this->string(255), // ->defaultValue(''),
            'body'      => $this->text()->notNull(),
            'user_id'   => $this->integer(),
            'ip'        => $this->string(255),
            'browser'   => $this->string(255),
            'create_at' => $this->timestamp(),
        ]);
        //$this->createIndex("{$this->idxNamePrefix}-user-id",  $this->tableName, 'user_id');
    }

    public function safeDown()
    {
        //echo basename(__FILE__, '.php') . " cannot be reverted.\n";
        //return false;
        $this->dropTable($this->tableName);
    }

}
