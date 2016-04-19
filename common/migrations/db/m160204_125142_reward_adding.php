<?php

use yii\db\Schema;
use yii\db\Migration;

class m160204_125142_reward_adding extends Migration
{
    public function up()
    {
        $this->insert('{{%settings}}',[
            'id'=>4,
            'name'=>'Reward after payment (percent)',
            'value'=>25,
            'description'=>'Percent of payment for reward.'
        ]);
    }

    public function down()
    {
        echo "m160204_125142_reward_adding cannot be reverted.\n";

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
