<?php

use yii\db\Schema;
use yii\db\Migration;

class m160203_102359_inquiry_comment extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Inquiry::tableName(), 'comment', $this->text());
    }

    public function down()
    {
        echo "m160203_102359_inquiry_comment cannot be reverted.\n";

        return false;
    }}
