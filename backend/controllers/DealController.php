<?php

namespace backend\controllers;

use Yii;
use backend\models\Deal;
use backend\models\DealSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DealController implements the CRUD actions for Deal model.
 */
class DealController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        //'actions' => ['logout', 'index'],
                        'actions' => ['logout', 'index','create','update','view','delete','flagged','suspended',
                        'dosuspend','doactive'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Deal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DealSearch();
        $queryParams = Yii::$app->request->queryParams;
        $queryParams['DealSearch']['mystatus'] = [1, 2];
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFlagged(){

        $searchModel = new DealSearch();
        $queryParams = Yii::$app->request->queryParams;
        $queryParams['DealSearch']['mystatus'] = [0, 2, 3];
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('flagged', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionSuspended(){

        $searchModel = new DealSearch();
        $queryParams = Yii::$app->request->queryParams;
        $queryParams['DealSearch']['mystatus'] = [0, 1, 3];
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('suspended', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionDosuspend($id){

        //0 - active
        //1 - flagged
        //2 - suspend
        //3 - expired

        $time = new \DateTime('now');
        $now = Yii::$app->formatter->asDate($time,'php:Y-m-d H:i:s');
        $model = $this->findModel($id);
        $model->status = 2;//suspended
        $model->statusDatetime = $now;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Status is suspended!');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Status do not change!');
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionDoactive($id){

        //0 - active
        //1 - flagged
        //2 - suspend
        //3 - expired

        $time = new \DateTime('now');
        $now = Yii::$app->formatter->asDate($time,'php:Y-m-d H:i:s');
        $model = $this->findModel($id);
        $model->status = 0;//active
        $model->statusDatetime = $now;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Status is active!');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Status do not change!');
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }


    /**
     * Displays a single Deal model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Creates a new Deal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Deal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Deal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Deal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Deal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Deal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
