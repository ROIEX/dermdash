<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_122706_city_fix extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_city_state','{{%city}}');
        $this->dropTable('{{%city}}');
        $this->renameColumn(\common\models\UserProfile::tableName(),'city_id','state_id');
        $this->addColumn(\common\models\UserProfile::tableName(),'city',$this->string());
    }

    public function down()
    {
        echo "m151229_122706_city_fix cannot be reverted.\n";

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
