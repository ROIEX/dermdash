<?php

use yii\db\Schema;
use yii\db\Migration;

class m160217_152846_treatment_buttons extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Treatment::tableName(), 'buttons_in_row', $this->integer());
        $this->addColumn(\common\models\Treatment::tableName(), 'session_buttons_position', $this->integer());
    }

    public function down()
    {
        echo "m160217_152846_treatment_buttons cannot be reverted.\n";

        return false;
    }

}
