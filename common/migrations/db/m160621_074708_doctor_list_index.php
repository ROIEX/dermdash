<?php

use yii\db\Migration;

class m160621_074708_doctor_list_index extends Migration
{
    public function up()
    {
        $this->addForeignKey('inquiry_key', \common\models\InquiryDoctorList::tableName(), 'inquiry_id',\common\models\Inquiry::tableName(), 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('inquiry_key', \common\models\InquiryDoctorList::tableName());
    }
}
