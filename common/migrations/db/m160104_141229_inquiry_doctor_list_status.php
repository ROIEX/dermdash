<?php

use yii\db\Schema;
use yii\db\Migration;

class m160104_141229_inquiry_doctor_list_status extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\InquiryDoctorList::tableName(), 'status',$this->smallInteger());
        $this->addColumn(\common\models\InquiryDoctorList::tableName(), 'created_at',$this->integer());
    }

    public function down()
    {
        $this->dropColumn(\common\models\InquiryDoctorList::tableName(), 'status');
        $this->dropColumn(\common\models\InquiryDoctorList::tableName(), 'created_at');
    }
}
