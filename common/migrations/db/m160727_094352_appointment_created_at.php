<?php

use yii\db\Migration;

class m160727_094352_appointment_created_at extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Booking::tableName(), 'created_at', $this->integer());
    }

    public function down()
    {
        echo "m160727_094352_appointment_created_at cannot be reverted.\n";

        return false;
    }
}
