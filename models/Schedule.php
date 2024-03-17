<?php

namespace app\models;

use DateTime;
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
 * @property Team|null $nextTeam
 * @property Member|[] $allMember
 */
class Schedule extends \yii\db\ActiveRecord
{

    public $work_date_start, $work_date_end;

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
            [['work_date_start', 'work_date_end'], 'safe'],
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

    public function getNextTeam()
    {
        $nextOrder = $this->team->order + 1;

        $nextTeam = Team::find()->where([
            'order' => $nextOrder
        ])->one();

        if ($nextTeam != null) {
            return $nextTeam;
        }

        return Team::find()->where([
            'order' => 1
        ])->one();
    }

    public function getAllMember()
    {
        return $this->hasMany(Member::class, ['id_team' => 'id'])->via('team');
    }

    public function generate()
    {
        if ($this->work_date_start == null or $this->work_date_end == null or $this->id_team == null) {
            return false;
        }
        
        $work_date_start = new DateTime($this->work_date_start);
        $work_date_end = new DateTime($this->work_date_end);
        $id_team = $this->id_team;        

        while ($work_date_start <= $work_date_end) {
            
            // check if current date is saturday or sunday
            $day_of_week = $work_date_start->format('N');
            if ($day_of_week == 6 || $day_of_week == 7) {
                $work_date_start->modify('+1 day');
                continue;
            }

            $params = [
                'work_date' => $work_date_start->format('Y-m-d'),
                'id_team' => $id_team
            ];
            
            $model = $this->createOrUpdate($params);

            $id_team = $model->nextTeam->id;
            $work_date_start->modify('+1 day');
        }
        return true;
    }

    public function createOrUpdate($params=[])
    {
        $work_date = $params['work_date'];
        $id_team = $params['id_team'];

        $model = new Schedule();
        $modelExists = $model->find()->where([
            'work_date' => $work_date
        ])->one();

        if ($modelExists != null) {
            $modelExists->updateAttributes([
                'id_team' => $id_team
            ]);

            return $modelExists;
        }

        $model->work_date = $work_date;
        $model->id_team = $id_team;
        $model->save();
        return $model;
    }

    public static function get3MonthScheduled()
    {
        $data = [];

        // get current month
        $currentMonth = new DateTime(date('M'));
        $prevMonth = $currentMonth->modify('-1 Month');
        $nextMonth = $currentMonth->modify('+1 Month');

        $date_start = $prevMonth->format('Y-m-01');
        $date_end = $nextMonth->format('Y-m-t');

        $searchModel = new ScheduleSearch();
        $dataProvider = $searchModel->search([
            'date_start' => $date_start,
            'date_end' => $date_end
        ]);

        /** use custom css @see index-calendar  */
        foreach ($dataProvider->getModels() as $schedule) {

            $desc = [];
            $allMember = $schedule->team->allMember;
            foreach($allMember as $member) {
                $desc[] = $member->name;
            }

            $data[] = [
                'time' => $schedule->work_date,
                'cls' => 'bgc-' . $schedule->team->name,
                'desc' => strtoupper(join('<br/>', $desc)),
                'color_hex' => $schedule->team->color_hex
            ];
        }
        
        return $data;
    }

}
