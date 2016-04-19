<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_144142_comment extends Migration
{
    public function up()
    {
        $this->addColumn('{{%inquiry}}','comment',$this->text());
        $this->addColumn('{{%inquiry}}','base_url',$this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%inquiry}}','comment');
        $this->dropColumn(\common\models\InquiryPhoto::tableName(),'base_url');
    }
}
