<?php

use yii\db\Migration;

class m160322_074741_documents extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_documents}}',[
            'id'=>'pk',
            'user_id'=>$this->integer(),
            'file_base_url'=>$this->string(128),
            'file_path'=>$this->string(128),
            'type'=>$this->integer(2),
        ]);
    }

    public function down()
    {
        echo "m160322_074741_documents cannot be reverted.\n";

        return false;
    }
}
