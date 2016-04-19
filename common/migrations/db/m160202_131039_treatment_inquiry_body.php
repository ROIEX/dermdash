<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_131039_treatment_inquiry_body extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_inquiry_body_part_treat', \common\models\InquiryTreatment::tableName());

        $this->renameColumn(\common\models\InquiryTreatment::tableName(), 'body_part_treatment_id', 'additional_attribute_id');
    }

    public function down()
    {
        echo "m160202_131039_treatment_inquiry_body cannot be reverted.\n";

        return false;
    }

}
