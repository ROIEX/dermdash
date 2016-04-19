<?php

use yii\db\Schema;
use yii\db\Migration;

class m160127_101412_add_attr_relation extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_add_attr_relation','{{%add_attribute_item}}','attribute_id','{{%additional_attribute}}','id','cascade','cascade');
    }

    public function down()
    {
        echo "m160127_101412_add_attr_relation cannot be reverted.\n";

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
