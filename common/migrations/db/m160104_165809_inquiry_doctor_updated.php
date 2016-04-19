<?php

use yii\db\Schema;
use yii\db\Migration;

class m160104_165809_inquiry_doctor_updated extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\InquiryDoctorList::tableName(), 'updated_at',$this->integer());
    }

    public function down()
    {
        $this->dropColumn(\common\models\InquiryDoctorList::tableName(), 'updated_at');
    }
}
