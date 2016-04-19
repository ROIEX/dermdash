<?php

use yii\db\Migration;

class m160318_145725_item_desc extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\TreatmentParam::tableName(), 'reg_description', $this->string(128));
        $this->addColumn(\common\models\BrandParam::tableName(), 'reg_description', $this->string(128));
        $this->addColumn(\common\models\Treatment::tableName(), 'reg_description', $this->string(128));
        $this->addColumn(\common\models\Brand::tableName(), 'reg_description', $this->string(128));
    }

    public function down()
    {
        echo "m160318_145725_item_desc cannot be reverted.\n";

        return false;
    }
}
