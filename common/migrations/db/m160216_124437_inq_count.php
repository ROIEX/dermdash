<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_124437_inq_count extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%inquiry_brand}}', 'count');
    }

    public function down()
    {
        echo "m160216_124437_inq_count cannot be reverted.\n";

        return false;
    }
}
