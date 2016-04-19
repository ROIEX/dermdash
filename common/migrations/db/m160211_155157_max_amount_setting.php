<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_155157_max_amount_setting extends Migration
{
    public function up()
    {
        $this->insert(\common\models\Settings::tableName(),[
            'id'=>5,
            'name'=>'Maximum reward count on payment.',
            'value'=>20,
            'description'=>'Max count bonuses on payment.'
        ]);
    }

    public function down()
    {
        echo "m160211_155157_max_amount_setting cannot be reverted.\n";

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
