<?php

use yii\db\Migration;

class m160302_133232_brand_multiselect extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Brand::tableName(), 'param_multiselect', $this->integer(1));
    }

    public function down()
    {
        echo "m160302_133232_brand_multiselect cannot be reverted.\n";

        return false;
    }
}
