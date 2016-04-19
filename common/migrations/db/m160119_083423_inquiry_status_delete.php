<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_083423_inquiry_status_delete extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%inquiry}}', 'status');
    }

    public function down()
    {
        $this->addColumn('{{%inquiry}}', 'status', $this->integer(3));
    }
}
