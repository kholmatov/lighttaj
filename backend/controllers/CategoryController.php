<?php

namespace backend\controllers;

use Yii;
use backend\models\Category;
use backend\models\CategorySearch;
use backend\models\Deal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\assets\AssetManager;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
                        'actions' => ['logout', 'index', 'create', 'update', 'view', 'delete', 'activate', 'deactivate'],
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
        $this->backButton();
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

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 1;
            $model->file = UploadedFile::getInstance($model, 'file');
            if (!empty($model->file)) {
                //“Please add an icon 92x92 pix to create a category”
                $rs = $this->UploadIco($model);
                if ($rs) {
                    Yii::$app->session->setFlash('success', 'Category create success!');
                    return $this->redirect('index');
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Please add an png icon 96x96 pix to create a category!');
                return $this->render('create', [
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

    private function UploadIco($model)
    {

        $model->file = UploadedFile::getInstance($model, 'file');

        $categorypath = dirname(__FILE__) . '/../web/assets/category/';
        $success = true;
        if (!is_dir($categorypath)) {

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
        if ($size->getWidth() != AssetManager::CategoryItemWidth_big || $size->getHeight() != AssetManager::CategoryItemHeight_big) {
            Yii::$app->session->setFlash('error', "Please upload only PNG of size 96x96");
            return 0;
        }

        if ($model->file->type !== "image/png") {
            Yii::$app->session->setFlash('error', "Please upload only PNG format");
            return 0;
        }

        $model->save();

        $ext = $model->file->extension;
        $newFile = $categorypath . $model->id . '_big.' . $ext;
        $rsupload = $model->file->saveAs($newFile);

        if (!$rsupload) {
            Yii::$app->session->setFlash('error', "Error Upload!");
            return 0;
        }

        $med = $categorypath . $model->id . '_med.' . $ext;
        Image::frame($newFile, 0)
            ->thumbnail(new Box(AssetManager::CategoryItemWidth_med, AssetManager::CategoryItemHeight_med))
            ->save($med, ['quality' => 100]);

        $small = $categorypath . $model->id . '_small.' . $ext;
        Image::frame($newFile, 0)
            ->thumbnail(new Box(AssetManager::CategoryItemWidth_small, AssetManager::CategoryItemHeight_small))
            ->save($small, ['quality' => 100]);


        $rs = AssetManager::saveCategoryIcon($categorypath, $model->id);
        if ($rs == 1) {
            $model->file = 0;
            $model->hasIco = 1;
            $model->save();
            Yii::$app->session->setFlash('success', 'Icon upload success!');
            return 1;
        } else {
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
                if ($rs) {
                    Yii::$app->session->setFlash('success', 'Category update success!');
                    return $this->redirect('index');
                } else {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            } else {
                $model->save();
            }

            Yii::$app->session->setFlash('success', 'Category update success!');
            return $this->redirect('index');

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
        if (Deal::find()->where(['categoryID' => $id])->count()) {
            Yii::$app->session->setFlash('error', 'There are active deals for this category. For that reason this category cannot be deleted.');
            return $this->redirect(['index']);
        }

        $result = AssetManager::deleteCategoryIconFromS3($id);
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Category deleted.');
        return $this->redirect(['index']);
    }

    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 1;
        $model->save();
        Yii::$app->session->setFlash('success', 'Category is activated.');
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        if (Deal::find()->where(['categoryID' => $id])->count()) {
            Yii::$app->session->setFlash('error', 'There are active deals for this category. For that reason this category cannot be deactivated.');
            return $this->render('update', [
                'model' => $model,
            ]);
        }

        $model->status = 0;
        $model->save();
        Yii::$app->session->setFlash('success', 'Category is deactivated.');

        return $this->render('update', [
            'model' => $model,
        ]);

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

    private function backButton()
    {
        $session = Yii::$app->session;
        $session->open();
        $session->set('category_back', '');
        if (isset(Yii::$app->request->queryParams['page']) && isset(Yii::$app->request->queryParams['CategorySearch']['searchstring']))
            $session->set('category_back', '?CategorySearch[searchstring]=' . Yii::$app->request->queryParams['CategorySearch']['searchstring'] . '&page=' . Yii::$app->request->queryParams['page']);
        elseif (isset(Yii::$app->request->queryParams['page']))
            $session->set('category_back', '?page=' . Yii::$app->request->queryParams['page']);
        elseif (isset(Yii::$app->request->queryParams['CategorySearch']['searchstring']))
            $session->set('category_back', '?CategorySearch[searchstring]=' . Yii::$app->request->queryParams['CategorySearch']['searchstring']);
    }
}
