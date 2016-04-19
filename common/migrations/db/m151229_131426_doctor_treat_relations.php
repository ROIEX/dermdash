<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_131426_doctor_treat_relations extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_doctor_treat','{{%doctor_treatment}}','user_id',\common\models\User::tableName(),'id','cascade','cascade');
        $this->addForeignKey('fk_item_treat','{{%doctor_treatment}}','item_id','{{%treatment_item}}','id','cascade','cascade');
    }

    public function down()
    {
        echo "m151229_131426_doctor_treat_relations cannot be reverted.\n";

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
