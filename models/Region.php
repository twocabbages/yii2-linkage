<?php

namespace cabbage\modules\linkage\models;

use Yii;

/**
 * This is the model class for table "regions".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $type
 * @property string $abbr
 * @property string $gb_code
 * @property string $pinyin
 * @property integer $status
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['parent_id', 'type', 'status'], 'integer'],
            [['name', 'abbr', 'gb_code', 'pinyin'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'type' => Yii::t('app', 'Type'),
            'abbr' => Yii::t('app', 'Abbr'),
            'gb_code' => Yii::t('app', 'Gb Code'),
            'pinyin' => Yii::t('app', 'Pinyin'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
