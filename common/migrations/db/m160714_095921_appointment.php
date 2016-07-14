<?php

use yii\db\Migration;

class m160714_095921_appointment extends Migration
{
    public function up()
    {
        $this->createTable('{{%booking}}',[
            'id' => 'pk',
            'inquiry_id' => $this->integer(),
            'first_name' => $this->string(255),
            'lst_name' => $this->string(255),
            'email' => $this->string(255),
            'phone_number' => $this->string(255),
            'date' => $this->dateTime()
        ]);

        $this->addForeignKey('booking_inquiry', 'booking', 'inquiry_id', 'inquiry', 'id', 'cascade', 'cascade');

        $this->addColumn(\common\models\Payment::tableName(), 'purchase_type', $this->smallInteger());
    }

    public function down()
    {
        echo "m160714_095921_appointment cannot be reverted.\n";
        return false;
    }
}
