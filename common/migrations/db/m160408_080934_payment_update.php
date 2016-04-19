<?php

use yii\db\Migration;

class m160408_080934_payment_update extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Payment::tableName(), 'doctor_id', $this->integer());
        $this->addColumn(\common\models\Payment::tableName(), 'inquiry_id', $this->integer());
    }

    public function down()
    {
        echo "m160408_080934_payment_update cannot be reverted.\n";

        return false;
    }
}
