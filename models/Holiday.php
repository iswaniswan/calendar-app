<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "holiday".
 *
 * @property int $id
 * @property string $date
 * @property string|null $name
 * @property string|null $description
 */
class Holiday extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'holiday';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['date'], 'safe'],
            [['name', 'description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    public static function getAll()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'date');
    }

    public static function getListDateArray()
    {
        $arr = [];
        $models = static::find()->all();
        foreach ($models as $model) {
            $arr[] = $model->date;
        }

        return $arr;
    }

    /** $param accept id or date */
    public static function getName($param)
    {
        $text = 'error';

        $isDate = count(explode("-", $param)) >= 3;
        if ($isDate) {
            $model = static::find()->where([
                'date' => $param
            ])->one();

            if ($model != null) {
                $text = ucwords($model->name);
            }
        }

        if ((int) $param >= 0 and (int) $param <= 1000) {
            $model = static::findOne($param);

            if ($model != null) {
                $text = ucwords($model->name);
            }
        }

        return $text;
    }

}
