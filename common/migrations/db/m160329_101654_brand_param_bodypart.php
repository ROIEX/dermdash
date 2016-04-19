<?php

use yii\db\Migration;

class m160329_101654_brand_param_bodypart extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\BrandParam::tableName(), 'body_part', $this->integer());
    }

    public function down()
    {
        echo "m160329_101654_brand_param_bodypart cannot be reverted.\n";

        return false;
    }
}
