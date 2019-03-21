<?php

namespace backend\controllers;

use Yii;
use backend\models\UserAdmin;
use backend\models\UserAdminSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\PasswordForm;
//use common\models\LoginForm;
/**
 * AdminController implements the CRUD actions for UserAdmin model.
 */
class ManagerController extends Controller
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
                        'actions' => ['logout', 'index','create','update','view','delete','changepassword'],
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
     * Lists all UserAdmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserAdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->backButton();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserAdmin model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserAdmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserAdmin();
        if ($model->load(Yii::$app->request->post())) {
            if($model->password_hash!="") {
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);
            }else{
                Yii::$app->session->setFlash('error', 'Please insert the password correctly again!');
                //$model->status = 1;
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            //if($id == Yii::$app->user->identity->id) $model->status = 1;

            if($model->save()) Yii::$app->session->setFlash('success', 'User created success!');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //$model->status = 1;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserAdmin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($id == Yii::$app->user->identity->id) $model->status = 1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'User data update success!');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($id == Yii::$app->user->identity->id){
            Yii::$app->session->setFlash('error', 'You don\'t have permission to perform this action!');
        }else {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'User deleted success!');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserAdmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserAdmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserAdmin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //other code default

    public function actionChangepassword(){

        $model = new PasswordForm;
        $modeluser = UserAdmin::find()->where([
            'username'=>Yii::$app->user->identity->username
        ])->one();

        if($model->load(Yii::$app->request->post())){
            if($model->validate()){
                try{
                    $modeluser->password_hash = Yii::$app->security->generatePasswordHash($_POST['PasswordForm']['newpass']);
                    if($modeluser->save()){
                        Yii::$app->getSession()->setFlash(
                            'success','Password changed'
                        );
                        return $this->redirect(['index']);
                    }else{
                        Yii::$app->getSession()->setFlash(
                            'error','Password not changed'
                        );
                        return $this->redirect(['index']);
                    }
                }catch(Exception $e){
                    Yii::$app->getSession()->setFlash(
                        'error',"{$e->getMessage()}"
                    );
                    return $this->render('changepassword',[
                        'model'=>$model
                    ]);
                }
            }else{
                return $this->render('changepassword',[
                    'model'=>$model
                ]);
            }
        }else{
            return $this->render('changepassword',[
                'model'=>$model
            ]);
        }
    }

    private function backButton(){
        $session = Yii::$app->session;
        $session->open();
        $session->set('manager_back', '');
        if(isset(Yii::$app->request->queryParams['page']) && isset(Yii::$app->request->queryParams['UserAdminSearch']['searchstring']))
            $session->set('manager_back', '?UserAdminSearch[searchstring]='.Yii::$app->request->queryParams['UserAdminSearch']['searchstring'].'&page='.Yii::$app->request->queryParams['page']);
        elseif(isset(Yii::$app->request->queryParams['page']))
            $session->set('manager_back', '?page='.Yii::$app->request->queryParams['page']);
        elseif(isset(Yii::$app->request->queryParams['UserAdminSearch']['searchstring']))
            $session->set('manager_back', '?UserAdminSearch[searchstring]='.Yii::$app->request->queryParams['UserAdminSearch']['searchstring']);
    }

}
