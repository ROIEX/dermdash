<?php

use yii\db\Schema;
use yii\db\Migration;

class m160210_161037_brands_v10 extends Migration
{
    public function up()
    {
        $this->addColumn('{{%brand}}', 'per', $this->integer());
        $this->addColumn('{{%brand}}', 'is_dropdown', $this->integer(1));
    }

    public function down()
    {
        $this->dropColumn('{{%brand}}', 'per');
        $this->dropColumn('{{%brand}}', 'is_dropdown');
    }
}
