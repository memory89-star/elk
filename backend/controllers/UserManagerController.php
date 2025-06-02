<?php
namespace backend\controllers;

use common\models\search\UserSearch;
use common\models\User;
use common\modules\userProfile\models\UserExt;
use common\modules\userProfile\models\UserProfile;
use frontend\modules\employee\models\Card;
use frontend\modules\employee\models\Department;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserManagerController extends Controller
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin', 'developer', 'security_admin', 'support_operator'],
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'get-card'],
                        'allow' => true,
                        'roles' => ['admin', 'developer', 'support_operator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        // load filter from session
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        $session = Yii::$app->session;
        if (isset($queryParams[$searchModel->formName()])) {
            // remember user filter if user set it in user-manager
            $session->set('user-manager.user.filter', $queryParams);
        } else {
            // if filter is not set try to load from session (previously remembered filter)
            if ($session->has('user-manager.user.filter')) {
                $queryParams = $session->get('user-manager.user.filter');
                return $this->redirect(ArrayHelper::merge(['index'], $queryParams));
            }
        }
        $dataProvider = $searchModel->search($queryParams);
        $mainDepartments = ArrayHelper::map(Department::getMainDepartments(), 'id', 'fullName');

        $auth = Yii::$app->authManager;
        $authItems = ArrayHelper::map($auth->getRoles(), 'name', 'name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mainDepartments' => $mainDepartments,
            'authItems' => $authItems,
        ]);

    }

    public function actionCreate()
    {
        $user = new User();
        //$user->scenario = User::SCENARIO_CREATE;
        $profile = new UserProfile();
        $departments = ArrayHelper::map(Department::find()->orderBy('code')->all(), 'id', 'fullName');
        $cards = ArrayHelper::map(Card::getAvailableEmployees(), 'id', 'fullName');

        $post = Yii::$app->request->post();
        if ($user->load($post) && $profile->load($post)) {
            /*if (!isset(Yii::$app->params['domainName'])) {
                throw new InvalidConfigException(Yii::t('add', 'Перменная domainName не определена в настройках'));
            }
            // generate email and other fields
            $domainName = Yii::$app->params['domainName'];
            $user->email = "{$user->username}@$domainName";*/
            /*if (isset(Yii::$app->params['enableLdapAuth']) && Yii::$app->params['enableLdapAuth']) {
                // set password to 123456 if ldap auth enabled
                $user->plainPassword = '123456';
                $user->plainPasswordRepeat = '123456';
            }*/
            $user->blocked_at = null;
            $user->confirmed_at = time();
            $user->flags = 0;
            $user->setPassword('123456');
            $user->generateAuthKey();

            if (isset($post['UserProfile']['card_id']) && $post['UserProfile']['card_id']) {
                $card_id = $post['UserProfile']['card_id'];
                $card = Card::findOne($card_id);
                $department = $card['currentDepartment'];
                $mainDepartment = $department['mainDepartment'];

                $profile->department_id = $department->id;
                $profile->main_department_id = $mainDepartment->id;
            }

            if ($user->validate() && $profile->validate()) {
                $user->save();
                $profile->user_id = $user->id;
                if ($profile->save()) {
                    // send mail after registration
                    //self::notifyUserAfterRegistration($user->id);

                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', [
            'user' => $user,
            'profile' => $profile,
            'departments' => $departments,
            'cards' => $cards,
        ]);
    }

    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $user->scenario = 'update';
        $profile = UserProfile::findOne(['user_id' => $id]);
        $departments = ArrayHelper::map(Department::find()->orderBy('code')->all(), 'id', 'fullName');

        $availableEmployees = Card::getAvailableEmployees();
        $cards = ArrayHelper::map($availableEmployees, 'id', 'shortName');

        if (($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post()))) {
            /*if($card_id = Yii::$app->request->post('UserProfile')['card_id']) {
                $card = Card::findOne($card_id);
                $profile->secondname = $card->secondname;
                $profile->firstname = $card->firstname;
                $profile->thirdname = $card->thirdname;
                $profile->department_id = ArrayHelper::getValue($card['currentDepartment'], 'id');
            }*/

            $user->validate();
            $profile->validate();

            if ($user->save() && $profile->save()) {
                return $this->redirect(['index']);
            }

        }
        return $this->render('update', [
            'user' => $user,
            'profile' => $profile,
            'departments' => $departments,
            'cards' => $cards,
        ]);

    }

    /**
     * Finds the UserDatabases model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = \dektrium\user\models\User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Страница не найдена.'));
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $profile = UserProfile::findOne(['user_id' => $id]);
        if ($profile) {
            $profile->delete();
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionUserPermissions($id)
    {
        $model = $this->findModel($id);
        $availableIndicators = $model->getAvailableIndicators();
        $notAvailableIndicators = $model->getNotAvailableIndicators();

        return $this->render('user_permissions', [
            'availableIndicators' => $availableIndicators,
            'notAvailableIndicators' => $notAvailableIndicators,
        ]);
    }

    public static function notifyUserAfterRegistration($user_id)
    {
        $user = UserExt::findOne($user_id);

        $mail = Yii::$app->mailer->compose(
            'registration-html',
            ['user' => $user]
        )
            ->setSubject(Yii::t('app', 'Registration on the site {0}', Yii::t('app', 'Digital portal')))
            ->setFrom([Yii::$app->params['robotEmail'] => Yii::$app->name])
            ->setTo($user->email);

        $mail->send();
        // message is ready - send it!
        /*if ($mail->send()) {
            Yii::$app->getSession()->addFlash('success', Yii::t('app', 'Mail notification has sent successfully'));
        } else {
            Yii::$app->getSession()->addFlash('danger', Yii::t('app', 'Error while message sending'));
        }*/
    }

    public function actionGetCard($id)
    {
        $model = Card::findOne($id);

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model) {
            return $model;
        }
        return false;
    }

}