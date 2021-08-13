<?php

namespace app\controllers;

use app\components\UserComponent;
use app\models\User;
use yii\filters\{VerbFilter, AccessControl};
use yii\web\{Response, Controller};
use Yii;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->checkGuest();

        $userHelper = new UserComponent();
        $model = new User();

        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->validate(['password'])) {

                if ($userHelper->authUser($model->password, $model->email)) {
                    return $this->goHome();
                } else {
                    $model->addError('password', 'Неправильная пара email/пароль.');
                }
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Register users
     *
     * @return string
     */
    public function actionRegister()
    {
        $this->checkGuest();
        $model = new User(['scenario' => User::SCENARIO_REGISTER]);

        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->validate(['email', 'parent_partner_id', 'password'])
                && $model->save(false)) {

                Yii::$app->session->setFlash('success', "Вы успешно зарегистрировались");
                return $this->goHome();
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return Response
     */
    private function checkGuest()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
    }
}
