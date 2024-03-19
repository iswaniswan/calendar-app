<?php

namespace app\controllers;

use app\models\Schedule;
use app\models\ScheduleSearch;
use app\models\Subscription;
// use Minishlink\WebPush\Subscription;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScheduleController implements the CRUD actions for Schedule model.
 */
class ScheduleController extends Controller
{
    
    protected $allowedCorsOrigin = [
        'http://localhost',
        'https://localhost',
        'http://127.0.0.1',
        'https://localhost',
    ];

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Define CORS filter only for specific action
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['http'],
                'Access-Control-Request-Method' => ['GET', 'POST'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Headers' => ['content-type'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600, 
            ],
            // Specify the action(s) to apply CORS to
            'actions' => [
                'save-subscription',
            ],
        ];

        return array_merge(
            $behaviors,
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
     * Lists all Schedule models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ScheduleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Schedule model.
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
     * Creates a new Schedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Schedule();

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
     * Updates an existing Schedule model.
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
     * Deletes an existing Schedule model.
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
     * Finds the Schedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Schedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Schedule::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCalendar()
    {        
        // get 3 month schedule, 1 month before, current month and 1 month next
        $dataSchedule = Schedule::get3MonthScheduled();
        
        return $this->render('index-calendar', [
            'dataSchedule' => $dataSchedule
        ]);
    }

    public function actionGenerate()
    {
        $model = new Schedule();

        if ($this->request->isPost 
            and $model->load($this->request->post()) 
            and $model->work_date_start 
            and $model->work_date_end
            and $model->id_team) {
            
                if ($model->generate()) {
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                } else {
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan.');
                }
        } 

        return $this->redirect(['setting/index']);
    }

    public function actionSaveSubscription()
    {
        $response = [
            'error' => 'Method Not Allowed', 
            'message' => 'This action only supports POST requests.'
        ];

        if (Yii::$app->request->isPost) {
            // Parse the request body as JSON
            $rawBody = Yii::$app->request->getRawBody();
            $data = json_decode($rawBody, true);

            $model = new Subscription();
            $model->find()->where([
                'auth' => $data['keys']['auth']
            ])->one();

            // update
            if ($model != null) {
                $model->updateAttributes([
                    'end_point' => $data['endpoint'],
                    'expiration_time' => $data['expirationTime'],
                    'auth' => $data['keys']['auth'],
                    'p256dh' => $data['keys']['p256dh'],
                    'raw' => $rawBody    
                ]);

                $response = [
                    'success' => true, 
                    'message' => 'Subscription data received and updated successfully.'
                ];                

            } else {
                //create
                $model = new Subscription([
                    'end_point' => $data['endpoint'],
                    'expiration_time' => $data['expirationTime'],
                    'auth' => $data['keys']['auth'],
                    'p256dh' => $data['keys']['p256dh'],
                    'raw' => $rawBody
                ]);
    
                if ($model->save()) {
                    $response = [
                        'success' => true, 
                        'message' => 'Subscription data received and saved successfully.'
                    ];
                } else {
                    $response['message'] = $model->error;
                }
            }

        } 
        
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        return $response;
    }

}
