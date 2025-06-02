<?php
/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$moduleNameConf = \yii\helpers\Inflector::camel2id($generator->moduleID);
$className = \yii\helpers\Inflector::id2camel($generator->moduleID);

echo "<?php\n";
?>

use frontend\modules\<?= $generator->moduleID ?>\assets\<?= $className ?>Asset;

<?= $className ?>Asset::register($this);

<?php echo "?>\n"; ?>

<div class="<?= $generator->moduleID . '-default-index' ?>">
    <h1><?= "<?= " ?>$this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= "<?= " ?>$this->context->action->id ?>".
        The action belongs to the controller "<?= "<?= " ?>get_class($this->context) ?>"
        in the "<?= "<?= " ?>$this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= "<?= " ?>__FILE__ ?></code>
    </p>
    <p>
        Добавьте в frontend/config/main :<br/>
        <code>
            <pre>
            'modules' => [
                ...
                '<?= "<?= " ?>$this->context->module->id ?>' => [
                    'class' => 'frontend\modules\<?= "<?= " ?>$this->context->module->id ?>\Module',
                    'as access' => [
                        'class' => 'yii\filters\AccessControl',
                        'rules' => [
                            [
                                'allow' => true,
                                'roles' => ['admin', 'security_admin', '_user', '_manager', '_admin']
                            ]
                        ]
                    ],
                ],
                ...
            ],
            </pre>
        </code>
    </p>
    <p>
        Добавьте в console/config/main :<br/>
        <code>
            <pre>
            'controllerMap' => [
                ...
                // create migration in module <?= $generator->moduleID ?>
                'migrate-<?= $moduleNameConf ?>' => [
                    'class' => 'yii\console\controllers\MigrateController',
                    'migrationNamespaces' => ['frontend\<?= $generator->moduleID ?>\migrations'],
                    'migrationPath' => '@modules/<?= $generator->moduleID ?>/migrations'
                ]
                ...
            ],
            </pre>
        </code>
    </p>
</div>
