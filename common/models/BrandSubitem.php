<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "brand_subitem".
 * Contains created by admin sub items of brands with specific treatment type count
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $type_count_id
 * @property integer $body_part_id
 */
class BrandSubitem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_subitem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'type_count_id', 'body_part_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'type_count_id' => Yii::t('app', 'Type Count ID'),
            'body_part_id' => Yii::t('app', 'Body Part ID'),
        ];
    }
}
