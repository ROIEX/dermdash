<?php

use yii\db\Migration;

class m160224_102804_intensity extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\InquiryTreatment::tableName(),'treatment_intensity_id',$this->integer());

        $this->addForeignKey('fk_intensity_treatment',\common\models\InquiryTreatment::tableName(),'treatment_intensity_id',\common\models\TreatmentIntensity::tableName(),'id','cascade','cascade');
    }

    public function down()
    {
        echo "m160224_102804_intensity cannot be reverted.\n";

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
