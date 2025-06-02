<?php
namespace backend\controllers;

use backend\models\Git;
use common\components\GitManager;
use common\exceptions\CLIException;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class GitController extends Controller
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
                        'actions' => ['login', 'logout', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin', 'developer', 'security_admin'],
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
     * @return string
     */
    public function actionIndex()
    {
        /**
         * @var $git GitManager
         */
        $git = Yii::$app->git;

        $cliResult = null;
        $commandException = null;

        $model = new Git();

        if (Yii::$app->request->isPost) {
            $button = Yii::$app->request->post('submit-button', null);

            try {
                switch ($button) {
                    case 'git-pull':
                        $cliResult = $git->pull();
                        break;
                    case 'migrate-up':
                        $cliResult = $git->migrateUp();
                        break;
                    case 'console-command':
                        if ($model->load(Yii::$app->request->post())) {
                            $cmd = ArrayHelper::getValue(Yii::$app->request->post('Git'), 'console_command');
                            if ($cmd) {
                                $cliResult = $git->execute($cmd);
                            }
                        }
                        break;
                }
            } catch (CLIException $e) {
                $commandException = $e;
            }

        }

        $status = $git->status();
        $summary = $git->execute("git show --summary");
        $lastTag = $git->execute("git describe --abbrev=0");

        return $this->render('index', [
            'status' => $status,
            'summary' => $summary,
            'lastTag' => $lastTag,
            'cliResult' => $cliResult,
            'commandException' => $commandException,
            'model' => $model,
        ]);

    }


}