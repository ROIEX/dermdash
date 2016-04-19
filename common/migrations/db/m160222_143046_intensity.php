<?php

use yii\db\Schema;
use yii\db\Migration;

class m160222_143046_intensity extends Migration
{
    public function up()
    {
        $this->createTable("{{%intensity}}",[
            'id' => 'pk',
            'name' => $this->string(),
        ]);

        $this->createTable("{{%treatment_intensity}}",[
            'id' => 'pk',
            'treatment_id' => $this->integer(),
            'intensity_id' => $this->integer(),
            'brand_param_id' => $this->integer(),
            'count' => $this->integer(),

            'status'=>$this->smallInteger()
        ]);
    }

    public function down()
    {
        echo "m160222_143046_intensity cannot be reverted.\n";
        return false;
    }

}
