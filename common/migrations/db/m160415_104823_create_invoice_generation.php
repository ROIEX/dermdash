<?php

use yii\db\Migration;

class m160415_104823_create_invoice_generation extends Migration
{
    public function up()
    {
        $this->createTable('invoice_generation', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('invoice_generation');
    }
}
