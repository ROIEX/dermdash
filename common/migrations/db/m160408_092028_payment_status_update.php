<?php

use yii\db\Migration;

class m160408_092028_payment_status_update extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Payment::tableName(), 'offer_status', $this->integer());
        $this->addColumn(\common\models\Payment::tableName(), 'invoice_status', $this->integer());
    }

    public function down()
    {
        echo "m160408_092028_payment_status_update cannot be reverted.\n";

        return false;
    }
}
