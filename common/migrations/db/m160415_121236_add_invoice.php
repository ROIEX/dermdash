
<?php

use yii\db\Migration;

class m160415_121236_add_invoice extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Invoice::tableName(), 'date_id', $this->integer());
    }

    public function down()
    {
        echo "m160415_121236_add_invoice cannot be reverted.\n";

        return false;
    }
}
