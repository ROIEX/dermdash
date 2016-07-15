<?php

use yii\db\Migration;

class m160715_112137_delete_paym_type extends Migration
{
    public function up()
    {
        $this->dropColumn(\common\models\Payment::tableName(), 'purchase_type');
    }

    public function down()
    {
        echo "m160715_112137_delete_paym_type cannot be reverted.\n";

        return false;
    }
}
