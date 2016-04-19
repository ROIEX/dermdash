<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_160514_doctor_field extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%doctor}}', 'npi');
        $this->dropColumn('{{%doctor}}', 'license_exp');
    }

    public function down()
    {
        echo "m160211_160514_doctor_field cannot be reverted.\n";

        return false;
    }
}
