<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_documents".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $file_base_url
 * @property string $file_path
 * @property integer $type
 */
class UserDocuments extends \yii\db\ActiveRecord
{
    const BUSINESS_ASSOCIATE_AGREEMENT = 0;
    const INDEPENDENT_CONTRACTOR_AGREEMENT = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_documents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type'], 'integer'],
            [['file_base_url', 'file_path'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'file_base_url' => Yii::t('app', 'File Base Url'),
            'file_path' => Yii::t('app', 'File Path'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    public function savePdf(array $pdf, $user_id)
    {
        $this->user_id = $user_id;
        $this->file_base_url = Yii::getAlias('@storage/web/source/pdf/');
        $this->file_path = $pdf['filename'];
        $this->type = $pdf['type'];

        if(!$this->save()) {
            return false;
        }

        return true;
    }

    public static function getDocumentName($user_id, $document_type)
    {
        $document = self::findOne(['user_id' => $user_id, 'type' => $document_type]);
        return $document->file_base_url . '/' .$document->file_path;
    }
}
