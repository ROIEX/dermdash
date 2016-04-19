<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_111422_inquiry_created_at extends Migration
{
    public function up()
    {
        $this->addColumn('{{%inquiry}}','created_at',$this->integer());
    }

    public function down()
    {
        echo "m160128_111422_inquiry_created_at cannot be reverted.\n";

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
