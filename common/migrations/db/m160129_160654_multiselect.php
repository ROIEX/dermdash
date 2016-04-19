<?php

use yii\db\Schema;
use yii\db\Migration;

class m160129_160654_multiselect extends Migration
{
    public function up()
    {
        $this->addColumn('{{%treatment}}','param_multiselect',$this->smallInteger());
        $this->addColumn('{{%treatment}}','body_part_multiselect',$this->smallInteger());
    }

    public function down()
    {
        echo "m160129_160654_multiselect cannot be reverted.\n";

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
