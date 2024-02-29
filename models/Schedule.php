<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schedule".
 *
 * @property int $id
 * @property string|null $work_date
 * @property string|null $date_created
 * @property string|null $date_updated
 * @property string|null $date_deleted
 * @property int|null $id_team
 * @property Team|null $team
 * @property Member|[] $allMember
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_date', 'date_created', 'date_updated', 'date_deleted'], 'safe'],
            [['id_team'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_date' => 'Work Date',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'date_deleted' => 'Date Deleted',
            'id_team' => 'Id Team',
        ];
    }

    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'id_team']);
    }

    public function getAllMember()
    {
        return $this->hasMany(Member::class, ['id_team' => 'id'])->via('team');
    }

}
