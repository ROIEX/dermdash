<?php

use yii\db\Schema;
use yii\db\Migration;

class m151226_131711_inquiry_status extends Migration
{
    public function up()
    {
        $this->addColumn('{{%inquiry}}', 'status', $this->smallInteger());
    }

    public function down()
    {
        $this->dropColumn('{{%inquiry}}', 'status');
    }

}
