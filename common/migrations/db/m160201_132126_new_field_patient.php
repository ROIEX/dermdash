<?php

use yii\db\Schema;
use yii\db\Migration;

class m160201_132126_new_field_patient extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_profile}}','state_notification',$this->smallInteger());
    }

    public function down()
    {
        echo "m160201_132126_new_field_patient cannot be reverted.\n";

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
