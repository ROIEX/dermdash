<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_105523_delete_bonus_status extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_bonus_user','{{%bonus}}');
        $this->dropTable('{{%bonus}}');

        $this->addColumn(\common\models\InquiryDoctorList::tableName(),'paid_at',$this->integer());
    }

    public function down()
    {
        echo "m160121_105523_delete_bonus_status cannot be reverted.\n";

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
