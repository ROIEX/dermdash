<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_123634_fk_add extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_state_profile',\common\models\UserProfile::tableName(),'state_id',\common\models\State::tableName(),'id');
    }

    public function down()
    {
        echo "m151229_123634_fk_add cannot be reverted.\n";

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
