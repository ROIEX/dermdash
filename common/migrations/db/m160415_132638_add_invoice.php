<?php

use yii\db\Migration;

class m160415_132638_add_invoice extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Invoice::tableName(), 'net_total', $this->float());
    }

    public function down()
    {
        echo "m160415_132638_add_invoice cannot be reverted.\n";

        return false;
    }
}
