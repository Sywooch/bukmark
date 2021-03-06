<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

// set nav menu items
$navItems = [
	['label' => 'Home', 'url' => ['/site/index']],
	['label' => 'Proveedores', 'url' => ['/supplier/index']],
	['label' => 'Productos', 'url' => ['/product/index']],
	['label' => 'Clientes', 'url' => ['/client/index']],
	['label' => 'Presupuestos', 'url' => ['/estimate/index']],
	['label' => 'Facturas', 'url' => ['/receipt/index']],
	['label' => 'Resumen', 'url' => ['/summary/index']],
	Yii::$app->user->isGuest ?
		['label' => 'Login', 'url' => ['/site/login']] :
		['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
			'url' => ['/site/logout'],
			'linkOptions' => ['data-method' => 'post']],
];

// add users admin or profile menu entry when needed
$user = User::getActiveUser();
$newEntries = [];
if ($user) {
	if ($user->admin) {
		$newEntries[] = ['label' => 'Usuarios', 'url' => ['/user/index']];
	} else {
		$newEntries[] = ['label' => 'Perfil', 'url' => ['/user/update', 'id' => $user->id]];
	}
}
$navItems = array_merge($navItems, $newEntries);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $navItems,
            ]);
            NavBar::end();
        ?>

        <div class="container-fluid">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
