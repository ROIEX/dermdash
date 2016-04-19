<?php

use yii\db\Migration;

class m160229_162641_inquiry_list_viewed extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\InquiryDoctorList::tableName(), 'is_viewed_by_patient', $this->integer(1));
    }

    public function down()
    {
        echo "m160229_162641_inquiry_list_viewed cannot be reverted.\n";

        return false;
    }
}
