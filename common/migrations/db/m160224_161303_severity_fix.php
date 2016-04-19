<?php

use yii\db\Migration;

class m160224_161303_severity_fix extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_severity_inquiry',\common\models\InquiryTreatment::tableName());
        $this->renameColumn(\common\models\InquiryTreatment::tableName(),'severity_param_id','severity_id');
    }

    public function down()
    {
        $this->renameColumn(\common\models\InquiryTreatment::tableName(),'severity_id','severity_param_id');

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
