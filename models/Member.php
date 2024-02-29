<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $id_team
 * @property string|null $date_created
 * @property string|null $date_updated
 * @property string|null $date_deleted
 * @property Team|null $team
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_team'], 'integer'],
            [['date_created', 'date_updated', 'date_deleted'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'id_team' => 'Id Team',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'date_deleted' => 'Date Deleted',
        ];
    }

    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'id_team']);
    }
}
