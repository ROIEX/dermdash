<?php

use yii\db\Migration;

class m160411_112912_create_invoice extends Migration
{
    public function up()
    {
        $this->createTable('invoice', [
            'id' => $this->primaryKey(),
            'number' => $this->string(128),
            'user_id' => $this->integer(),
            'file_path' => $this->string(128)
        ]);
    }

    public function down()
    {
        $this->dropTable('invoice');
    }
}
