<?php

use yii\db\Schema;
use yii\db\Migration;

class m160201_100157_inquiry_phoyo_url extends Migration
{
    public function up()
    {
        $this->addColumn('{{%inquiry_photo}}','base_url',$this->string());
    }

    public function down()
    {
        echo "m160201_100157_inquiry_phoyo_url cannot be reverted.\n";

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
