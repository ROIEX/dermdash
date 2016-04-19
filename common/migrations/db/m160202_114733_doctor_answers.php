<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_114733_doctor_answers extends Migration
{
    public function up()
    {
        $this->createTable('{{%doctor_answer}}',[
            'id'=>'pk',
            'inquiry_doctor_list_id'=>$this->integer(),
            'answer'=>$this->text()
        ]);

        $this->addForeignKey('fk_answer_inquiry','{{%doctor_answer}}','inquiry_doctor_list_id',\common\models\InquiryDoctorList::tableName(),'id','cascade','cascade');
    }

    public function down()
    {
        echo "m160202_114733_doctor_answers cannot be reverted.\n";

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
