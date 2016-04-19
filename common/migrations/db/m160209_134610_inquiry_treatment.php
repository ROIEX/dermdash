<?php

use common\models\InquiryTreatment;
use common\models\TreatmentParamSeverity;
use yii\db\Schema;
use yii\db\Migration;

class m160209_134610_inquiry_treatment extends Migration
{
    public function up()
    {
        $this->addColumn(InquiryTreatment::tableName(),'severity_param_id',$this->integer());

        $this->addForeignKey('fk_severity_inquiry', InquiryTreatment::tableName(),'severity_param_id', TreatmentParamSeverity::tableName(),'id','cascade','cascade');
    }

    public function down()
    {
        echo "m160209_134610_inquiry_treatment cannot be reverted.\n";

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
