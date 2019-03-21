<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use backend\models\User;
use backend\models\Deal;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
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
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        // *User status
        // 0 - Active
        // 1 - Suspended
        $user_total_count = User::find()->count();

        // *Deal status
        //0 - active
        //1 - flagged
        //2 - suspend
        //3 - expired
        $deal_total_count = Deal::find()->count();
        $deal_active_count = Deal::find()->where(['status'=>0])->count();
        $deal_flagged_count = Deal::find()->where(['status'=>1])->count();
        $deal_suspended_count = Deal::find()->where(['status'=>2])->count();
        $deal_exprired_count = Deal::find()->where(['status'=>3])->count();
        //$deal_today_count = Deal::find()->where('dateCreated = now()')->count();

        return $this->render('index',
            [
                'user_total_count' => $user_total_count,
                'deal_total_count'  => $deal_total_count,
                'deal_active_count' => $deal_active_count,
                'deal_flagged_count' => $deal_flagged_count,
                'deal_suspended_count' => $deal_suspended_count,
                'deal_expired_count' => $deal_exprired_count
            ]
        );
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
