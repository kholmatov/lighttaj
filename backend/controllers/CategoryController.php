<?php

namespace backend\controllers;

use Yii;
use backend\models\Category;
use backend\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\assets\AssetManager;
use yii\web\UploadedFile;
use yii\imagine\Image;
//use yii\imagine\Box;
use Imagine\Image\Box;
/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{

    const CategoryItemWidth_big    = 96;
    const CategoryItemHeight_big   = 96;

    const CategoryItemWidth_med    = 64;
    const CategoryItemHeight_med   = 64;

    const CategoryItemWidth_small    = 32;
    const CategoryItemHeight_small   = 32;

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
                        'actions' => ['logout', 'index','create','update','view','delete','flagged','suspended','dosuspend','doactive'],
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        //print_r($elb);
        if ($model->load(Yii::$app->request->post())) {
            //return $this->redirect(['view', 'id' => $model->id]);
            $model->file = UploadedFile::getInstance($model, 'file');
            if (!empty($model->file)) {
                //“Please add an icon 92x92 pix to create a category”
                $rs = $this->UploadIco ($model);
                if ($rs) {
                    Yii::$app->session->setFlash ('success', 'Category create success!');
                    return $this->redirect ('index');
                } else {
                    return $this->render ('create', [
                        'model' => $model,
                    ]);
                }
            }else{
                Yii::$app->session->setFlash ('error', 'Please add an png icon 96x96 pix to create a category!');
                return $this->render ('create', [
                    'model' => $model,
                ]);
            }

        } else {
            //Yii::$app->session->setFlash('error', 'Some error!');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    private function UploadIco($model){

        $model->file = UploadedFile::getInstance($model, 'file');

        $categorypath = dirname(__FILE__).'/../web/assets/category/';
        $success = true;
        if (!is_dir( $categorypath)) {
            $success = mkdir($categorypath, 0777, true);
        }

        if (!$success) {
            Yii::$app->session->setFlash('error', "Can't create category directory");
            return 0;
        }
       // $file =  UploadedFile::getInstanceByName('file');

        $image = new Image();
        $open = $image->getImagine()->open($model->file->tempName);
        $size = $open->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();
        if ($size->getWidth() != self::CategoryItemWidth_big || $size->getHeight() != self::CategoryItemHeight_big) {
            Yii::$app->session->setFlash('error', "Please upload only PNG of size 96x96");
            return 0;
        }

        if ($model->file->type !== "image/png") {
            Yii::$app->session->setFlash('error', "Please upload only PNG format");
            return 0;
        }

        $model->save();

        $ext=$model->file->extension;
        $newFile = $categorypath.$model->id.'_big.'.$ext;
        $rsupload = $model->file->saveAs($newFile);

        if(!$rsupload){
            Yii::$app->session->setFlash('error', "Error Upload!");
            return 0;
        }

        $med = $categorypath.$model->id.'_med.'.$ext;
        Image::frame($newFile)
            ->thumbnail(new Box(self::CategoryItemWidth_med, self::CategoryItemHeight_med))
            ->save($med, ['quality' => 100]);

        $small = $categorypath.$model->id.'_small.'.$ext;
        Image::frame($newFile)
            ->thumbnail(new Box(self::CategoryItemWidth_small, self::CategoryItemHeight_small))
            ->save($small, ['quality' => 100]);


       $rs = AssetManager::saveCategoryIcon($categorypath, $model->id);
        if($rs==1){
            $model->file = 0;
            $model->hasIco = 1;
            $model->save();
            Yii::$app->session->setFlash('success', 'Icon upload success!');
            return 1;
        }else{
             Yii::$app->session->setFlash('error', $rs);
            return 0;
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if (!empty($model->file)) {
               $rs = $this->UploadIco($model);
                if($rs) {
                    Yii::$app->session->setFlash ('success', 'Category update success!');
                    return $this->redirect ('index');
                }else{
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }else{
                $model->save();
            }

            Yii::$app->session->setFlash ('success', 'Category update success!');
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect ('index');

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Success delete!');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
