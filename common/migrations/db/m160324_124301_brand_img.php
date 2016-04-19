<?php

use yii\db\Migration;

class m160324_124301_brand_img extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\BrandParam::tableName(), 'icon_base_url', $this->string(128));
        $this->addColumn(\common\models\BrandParam::tableName(), 'icon_path', $this->string(128));
    }

    public function down()
    {
        echo "m160324_124301_brand_img cannot be reverted.\n";

        return false;
    }

}
