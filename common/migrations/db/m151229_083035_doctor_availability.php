<?php

use common\models\Doctor;
use yii\db\Schema;
use yii\db\Migration;

class m151229_083035_doctor_availability extends Migration
{
    public function up()
    {
        $this->addColumn(Doctor::tableName(),'status',$this->smallInteger());
    }

    public function down()
    {
        $this->dropColumn(Doctor::tableName(),'status');
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
