<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_130551_treat_relation extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_treatment_category','{{%treatment_item}}','category_id','{{%treatment_category}}','id','cascade','cascade');
    }

    public function down()
    {
        echo "m151229_130551_treat_relation cannot be reverted.\n";

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
