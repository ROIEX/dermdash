<?php

use yii\db\Schema;
use yii\db\Migration;

class m160218_094801_inquiry_doctor_list_badge extends Migration
{
    public function up()
    {
        $this->addColumn('{{%inquiry_doctor_list}}', 'is_viewed', $this->integer(2));
    }

    public function down()
    {
        echo "m160218_094801_inquiry_doctor_list_badge cannot be reverted.\n";

        return false;
    }
}
