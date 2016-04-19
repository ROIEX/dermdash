<?php

use yii\db\Migration;

class m160406_115801_create_payment_items extends Migration
{
    public function up()
    {
        $this->createTable('payment_items', [
            'id' => $this->primaryKey(),
            'payment_history_id' => $this->integer(),
            'inquiry_doctor_list_id' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('payment_items');
    }
}
