<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use backend\models\Deal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\assets\AssetManager;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'actions' => ['logout', 'index','create','update','view','delete','dosuspend','doactive'],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->backButton();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
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
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->deletUserItems($id);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    private function deletUserItems($id){


            $query = "SELECT id, imageList FROM deal WHERE userId = $id";
            $result = Yii::$app->db->createCommand($query)->queryAll();

            if(count($result)){
                $myarray = Array();
                $key_array = Array();
                foreach($result as $rows){
                    $myarray[] = $rows['id'];

                    if(!empty($rows['imageList'])) {
                        $tempImageArray = AssetManager::fetchImageFilesForDealDelete($rows['id'],$rows['imageList']);
                        foreach($tempImageArray as $imageItem){
                            $key_array[] = Array('Key'=>$imageItem);
                        }

                    }
                }

                if(count($key_array)>0){
                    //delet images from s3;
                    $result = AssetManager::deletingMultiple($key_array);
                }

                Yii::$app->db->createCommand()->delete('user_deal_favorite', ['dealID' => $myarray])->execute();
                Yii::$app->db->createCommand()->delete('user_deal_like', ['dealID' => $myarray])->execute();
                Yii::$app->db->createCommand()->delete('deal', ['id' => $myarray])->execute();

            }

         Yii::$app->db->createCommand()->delete('user_deal_favorite', ['userID' => $id])->execute();
         Yii::$app->db->createCommand()->delete('user_deal_like', ['userID' => $id])->execute();
         Yii::$app->db->createCommand()->delete('token', ['userID' => $id])->execute();
         Yii::$app->db->createCommand()->delete('user_profile', ['userID' => $id])->execute();


    }

    public function actionDosuspend($id){
         //User Status
        //0 - active
        //1 - suspend
        $time = new \DateTime('now');
        $now = Yii::$app->formatter->asDate($time,'php:Y-m-d H:i:s');
        $model = $this->findModel($id);
        $model->status = 1;//suspended

        //Deal Status
        //0 - active
        //1 - flagged
        //2 - suspend
        //3 - expired
        $dl = Deal::updateAll(['status' =>'2','statusDatetime'=>$now],['userID'=>$model->id,'status'=>[0,1,2]]);
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Status is suspended!');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Status do not change!');
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionDoactive($id){
        //User Status
        //0 - active
        //1 - suspend
        $time = new \DateTime('now');
        $now = Yii::$app->formatter->asDate($time,'php:Y-m-d H:i:s');
        $model = $this->findModel($id);
        $model->status = 0;//suspended

        //Deal Status
        //0 - active
        //1 - flagged
        //2 - suspend
        //3 - expired
        //$dl = Deal::updateAll(['status' =>'2','statusDatetime'=>$now],['userID'=>$model->id,'status'=>[0,1,2]]);
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Status is active!');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Status do not change!');
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function backButton(){
        $session = Yii::$app->session;
        $session->open();
        $session->set('user_back', '');
        if(isset(Yii::$app->request->queryParams['page']) && isset(Yii::$app->request->queryParams['UserSearch']['searchstring']))
            $session->set('user_back', '?UserSearch[searchstring]='.Yii::$app->request->queryParams['UserSearch']['searchstring'].'&page='.Yii::$app->request->queryParams['page']);
        elseif(isset(Yii::$app->request->queryParams['page']))
            $session->set('user_back', '?page='.Yii::$app->request->queryParams['page']);
        elseif(isset(Yii::$app->request->queryParams['UserSearch']['searchstring']))
            $session->set('user_back', '?UserSearch[searchstring]='.Yii::$app->request->queryParams['UserSearch']['searchstring']);
    }
}
