<?php

use yii\db\Migration;

class m160223_165949_brand_provided_treatment extends Migration
{
    public function up()
    {
        $this->createTable("{{%brand_provided_treatment}}",[
            'id' => 'pk',
            'treatment_param_id' => $this->integer(),
            'brand_param_id' => $this->integer(),
            'count' => $this->integer(),
        ]);
    }

    public function down()
    {
        echo "m160223_165949_brand_provided_treatment cannot be reverted.\n";

        return false;
    }
}
