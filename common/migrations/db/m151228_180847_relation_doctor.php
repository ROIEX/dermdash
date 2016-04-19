<?php

use yii\db\Schema;
use yii\db\Migration;

class m151228_180847_relation_doctor extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_doctor_user',\common\models\Doctor::tableName(),'user_id',\common\models\User::tableName(),'id','cascade','cascade');
    }

    public function down()
    {
        echo "m151228_180847_relation_doctor cannot be reverted.\n";

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
