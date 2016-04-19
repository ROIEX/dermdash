<?php

use yii\db\Schema;
use yii\db\Migration;

class m160129_135041_doctor_items extends Migration
{
    public function up()
    {
        $this->renameColumn('{{%doctor_brand}}', 'sub_item_id', 'brand_param_id');
        $this->renameColumn('{{%doctor_treatment}}', 'item_id', 'treatment_param_id');

        $this->addColumn('{{%doctor_treatment}}', 'price', $this->integer());
        $this->dropTable('{{%brand_subitem}}');
    }

    public function down()
    {
        echo "m160129_135041_doctor_items cannot be reverted.\n";

        return false;
    }
}
