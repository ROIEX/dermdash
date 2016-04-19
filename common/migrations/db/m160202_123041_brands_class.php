<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_123041_brands_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%brand}}','type',$this->smallInteger());
    }

    public function down()
    {
        echo "m160202_123041_brands_class cannot be reverted.\n";

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
