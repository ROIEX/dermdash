<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_111600_treatment_v10 extends Migration
{
    public function up()
    {
        $this->createTable('{{%severity}}',[
            'id' => 'pk',
            'name' => $this->string(),
            'status' => $this->smallInteger(),
        ]);

        $this->createTable('{{%treatment_param_severity}}',[
            'id' => 'pk',
            'param_id' => $this->integer(),
            'severity_id' => $this->integer(),
            'brand_id' => $this->integer(),
            'count' => $this->integer(),
        ]);

        $this->addColumn('{{%treatment}}', 'select_both_button', $this->integer(1));
    }

    public function down()
    {
        echo "m160208_111600_treatment_v10 cannot be reverted.\n";

        return false;
    }
}
