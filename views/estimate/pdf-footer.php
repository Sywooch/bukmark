<?php

use yii\helpers\Html;
?>

<div style="background: #232323; color: lightgray; font-style: italic; vertical-align: middle; height: 30px; padding-top: 10px;">
	<div style="float:left; margin-left: 40px; width: 225px; text-align: right;">
		<?= Html::img('images/web.png') . ' ' . Yii::$app->params['website'] ?>
	</div>
	<div style="float:left; margin:0; width: 275px; text-align: center;">
		<?= Html::img('images/email.png') . ' ' . Yii::$app->params['contactEmail'] ?>
	</div>
	<div style="float:left; margin:0; width: 225px; text-align: left;">
		<?= Html::img('images/phone.png') . ' ' . Yii::$app->params['contactPhone'] ?>
	</div>
</div>

