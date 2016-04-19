<?php

use yii\db\Migration;

class m160216_104829_device_token_size extends Migration
{
    public function up()
    {
        $this->alterColumn(\common\models\UserDevice::tableName(),'device_token',$this->string());
    }

    public function down()
    {
        echo "m160216_104829_device_token_size cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
