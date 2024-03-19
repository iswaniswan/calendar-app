<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subscription".
 *
 * @property int $id
 * @property string|null $end_point
 * @property string|null $expiration_time
 * @property string|null $auth
 * @property string|null $p256dh
 * @property string|null $raw
 * @property bool|null $f_status
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth', 'p256dh', 'raw'], 'string'],
            [['f_status'], 'boolean'],
            [['end_point', 'expiration_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'end_point' => 'End Point',
            'expiration_time' => 'Expiration Time',
            'auth' => 'Auth',
            'p256dh' => 'P256dh',
            'raw' => 'Raw',
            'f_status' => 'F Status',
        ];
    }
}
