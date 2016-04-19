<?php

use common\models\State;
use yii\db\Schema;
use yii\db\Migration;

class m151229_105059_relation_city extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_city_state','{{%city}}','state_id', State::tableName(),'id','cascade','cascade');
    }

    public function down()
    {
        echo "m151229_105059_relation_city cannot be reverted.\n";

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
