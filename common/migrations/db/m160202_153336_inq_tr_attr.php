<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_153336_inq_tr_attr extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_inquiry_body_part_treat','{{%inquiry_treatment}}', 'additional_attribute_id', '{{%add_attribute_item}}', 'id', 'no action', 'no action');
    }

    public function down()
    {
        echo "m160202_153336_inq_tr_attr cannot be reverted.\n";

        return false;
    }
}
