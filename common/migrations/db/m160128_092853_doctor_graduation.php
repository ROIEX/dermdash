<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_092853_doctor_graduation extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%doctor}}', 'graduation_date');
        $this->dropColumn('{{%doctor}}', 'education');
        $this->dropColumn('{{%doctor}}', 'experience');
        $this->dropColumn('{{%doctor}}', 'dea');
        $this->dropColumn('{{%doctor}}', 'dea_exp');
    }

    public function down()
    {
        echo "m160128_092853_doctor_graduation cannot be reverted.\n";

        return false;
    }
}
