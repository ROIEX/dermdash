<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_100841_inquiry_update extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%inquiry_doctor_list}}', 'comment');
        $this->dropColumn('{{%inquiry_doctor_list}}', 'updated_at');
    }

    public function down()
    {
        echo "m160216_100841_inquiry_update cannot be reverted.\n";

        return false;
    }
}
