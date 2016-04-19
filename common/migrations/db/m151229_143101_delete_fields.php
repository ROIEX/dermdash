<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_143101_delete_fields extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%inquiry}}','photo_id');
        $this->dropColumn('{{%inquiry}}','doctor_list_id');
        $this->renameColumn('{{%inquiry_doctor_list}}','unquiry_id','inquiry_id');
        $this->addForeignKey('fk_doctor_list_inq','{{%inquiry_doctor_list}}','inquiry_id','{{%inquiry}}','id','cascade','cascade');
        $this->addForeignKey('fk_photo_inq','{{%inquiry_photo}}','inquiry_id','{{%inquiry}}','id','cascade','cascade');
    }

    public function down()
    {
        echo "m151229_143101_delete_fields cannot be reverted.\n";

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
