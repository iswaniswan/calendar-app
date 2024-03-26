<?php

namespace app\controllers;

use app\models\Holiday;
use app\models\Log;
use app\models\Schedule;
use Yii;
use app\models\Subscription;
use app\models\SubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use Minishlink\WebPush\Subscription As WebPushSubscription;
use Minishlink\WebPush\WebPush;

/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class SubscriptionController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Subscription models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SubscriptionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subscription model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subscription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Subscription();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Subscription model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Subscription model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Subscription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Subscription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subscription::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSendNotification()
    {        
        // check if today is holiday
        $current = date('Y-m-d');
        $holidays = Holiday::getListDateArray();
        if (in_array($current, $holidays)) {
            return;
        }
        
        $auth = [
            'VAPID' => Yii::$app->params['vapidKeys']
        ];
        
        $payload = [];

        // get schedule today        
        $model = Schedule::getToday();
        if ($model != null) {
            $allMember = @$model->allMember;
            $text = [];
            foreach (@$allMember as $member) {
                $text[] = ucwords($member->name);
            }

            $payload['title'] = 'Jadwal Kebersihan Hari Ini';
            $payload['body'] = join("\n", $text);
        }

        // payload empty = week end
        if (empty($payload)) {
            return;
        }

        $payload = json_encode($payload);

        $webPush = new WebPush($auth);

        $allSubscription = Subscription::findAll([
            'f_status' => true
        ]);

        foreach ($allSubscription as $subscription) {
            $raw = $subscription->raw;
            $param = json_decode($raw, true);

            $report = $webPush->sendOneNotification(
                WebPushSubscription::create($param), $payload, ['TTL' => 5000]
            );

            $model = new Log([
                'report' => json_encode($report)
            ]);
            $model->save();
        }

        $response = [
            'message' => 'success',
            'data' => []
        ];

        echo json_encode($response);
    }

}
