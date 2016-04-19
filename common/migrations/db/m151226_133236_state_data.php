<?php

use yii\db\Schema;
use yii\db\Migration;

class m151226_133236_state_data extends Migration
{
    public function up()
    {
        $this->truncateTable('{{%state}}');
        $this->addColumn('{{%state}}', 'status', $this->smallInteger());
        $sql = <<<SQL
            INSERT INTO `state` (`id`, `name`, `status`, `short_name`) VALUES
            (1, 'Alabama', 0 , 'AL'),
            (2, 'Alaska', 0, 'AK'),
            (3, 'Arizona', 0, 'AZ'),
            (4, 'Arkansas', 0, 'AR'),
            (5, 'California', 1, 'CA'),
            (6, 'Colorado', 0, 'CO'),
            (7, 'Connecticut', 0, 'CT'),
            (8, 'Delaware', 0, 'DE'),
            (9, 'Florida', 0, 'FL'),
            (10, 'Georgia', 0, 'GA'),
            (11, 'Hawaii', 0, 'HI'),
            (12, 'Idaho', 0, 'ID'),
            (13, 'Illinois', 0, 'IL'),
            (14, 'Indiana', 0, 'IN'),
            (15, 'Iowa', 0, 'IA'),
            (16, 'Kansas', 0, 'KS'),
            (17, 'Kentucky', 0, 'KY'),
            (18, 'Louisiana', 0, 'LA'),
            (19, 'Maine', 0,'ME'),
            (20, 'Maryland', 0, 'MD'),
            (21, 'Massachusetts', 0, 'MA'),
            (22, 'Michigan', 0, 'MI'),
            (23, 'Minnesota', 0, 'MN'),
            (24, 'Mississippi', 0, 'MS'),
            (25, 'Missouri', 0,'MO'),
            (26, 'Montana', 0, 'MT'),
            (27, 'Nebraska', 0, 'NE'),
            (28, 'Nevada', 0, 'NV'),
            (29, 'New Hampshire', 0, 'NH'),
            (30, 'New Jersey', 0, 'NJ'),
            (31, 'New Mexico', 0, 'NM'),
            (32, 'New York', 0, 'NY'),
            (33, 'North Carolina', 0, 'NC'),
            (34, 'North Dakota', 0, 'ND'),
            (35, 'Ohio', 0, 'OH'),
            (36, 'Oklahoma', 0, 'OK'),
            (37, 'Oregon', 0, 'OR'),
            (38, 'Pennsylvania', 0, 'PA'),
            (39, 'Rhode Island', 0, 'RI'),
            (40, 'South Carolina', 0, 'SC'),
            (41, 'South Dakota', 0, 'SD'),
            (42, 'Tennessee', 0, 'TN'),
            (43, 'Texas', 0, 'TX'),
            (44, 'Utah', 0, 'UT'),
            (45, 'Vermont', 0, 'VT'),
            (46, 'Virginia', 0, 'VA'),
            (47, 'Washington', 0, 'WA'),
            (48, 'West Virginia', 0, 'WV'),
            (49, 'Wisconsin', 0, 'WI'),
            (50, 'Wyoming', 0, 'WY');
SQL;
        $this->execute($sql);
    }


    public function down()
    {
        echo "m151226_133236_state_data cannot be reverted.\n";

        return false;
    }
}
