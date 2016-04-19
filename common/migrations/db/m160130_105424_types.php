<?php

use yii\db\Schema;
use yii\db\Migration;

class m160130_105424_types extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%user_profile}}','phone',$this->string());
        $this->alterColumn('{{%user_profile}}','city',$this->string());
        $this->alterColumn('{{%user_profile}}','address',$this->string());
    }

    public function down()
    {
        echo "m160130_105424_types cannot be reverted.\n";

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
