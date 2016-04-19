<?php

use yii\db\Schema;
use yii\db\Migration;

class m160205_135327_add_att extends Migration
{
    public function up()
    {
        $this->addColumn('{{%inquiry_treatment}}','additional_attribute_id',$this->integer());
        $this->addForeignKey('fk_inquiry_body_part_treat','{{%inquiry_treatment}}','additional_attribute_id','{{%add_attribute_item}}','id','cascade','cascade');

    }

    public function down()
    {
        echo "m160205_135327_add_att cannot be reverted.\n";

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
