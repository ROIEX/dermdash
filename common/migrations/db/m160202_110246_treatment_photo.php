<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_110246_treatment_photo extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Treatment::tableName(), 'icon_base_url', $this->string(256));
        $this->addColumn(\common\models\Treatment::tableName(), 'icon_path', $this->string(256));
    }

    public function down()
    {
        echo "m160202_110246_treatment_photo cannot be reverted.\n";

        return false;
    }
}
