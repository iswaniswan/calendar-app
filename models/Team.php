<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "team".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $color_hex
 * @property integer|null $order
 * @property string $date_created
 * @property string|null $date_updated
 * @property string|null $date_deleted
 * @property Member|null $allMember
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_created', 'date_updated', 'date_deleted'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['color_hex'], 'string', 'max' => 8],
            [['order'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'color_hex' => 'Color Hex',
            'order' => 'Urutan',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'date_deleted' => 'Date Deleted',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(static::find()->orderBy(['order' => SORT_ASC])->all(), 'id', 'name');
    }

    public function getAllMember()
    {
        return $this->hasMany(Member::class, ['id_team' => 'id']);
    }    

}
