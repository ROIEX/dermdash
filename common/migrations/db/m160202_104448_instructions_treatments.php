<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_104448_instructions_treatments extends Migration
{
    public function up()
    {
        $this->addColumn('{{%treatment}}','instruction',$this->text());
    }

    public function down()
    {
        echo "m160202_104448_instructions_treatments cannot be reverted.\n";

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
