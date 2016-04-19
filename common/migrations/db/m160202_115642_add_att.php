<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_115642_add_att extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_add_treatment_attribute', '{{%additional_attribute}}');
        $this->dropForeignKey('fk_add_attr_relation', \common\models\AdditionalAttributeItem::tableName());
        $this->renameColumn(\common\models\AdditionalAttributeItem::tableName(), 'attribute_id', 'treatment_id');

        $this->dropTable('{{%additional_attribute}}');
        $this->dropTable('{{%attribute_treatment_item}}');
    }

    public function down()
    {
        echo "m160202_115642_add_att cannot be reverted.\n";

        return false;
    }
}
