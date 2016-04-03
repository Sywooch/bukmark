<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Currency;
use app\models\Product;
use app\assets\EstimateEntryAsset;

/* @var $this yii\web\View */
/* @var $model app\models\EstimateEntry */
/* @var $form yii\widgets\ActiveForm */

$currencies = Currency::labels();

$this->registerJs('var productImagesUrl = "' . Url::to(['product/get-images', 'id' => 'placeholder']) . '";', View::POS_HEAD);
EstimateEntryAsset::register($this);
?>

<div class="estimate-entry-form">

	<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

	<?= $form->field($model, 'product_id')->dropDownList(Product::getDropdownData(), ['prompt' => 'Elegir producto', 'id' => 'product_id']) ?>
	
	<?= $form->field($model, 'product_image_id')->hiddenInput(['id' => 'product_image_id']) ?>
	
	<div class="form-group" id="images">
		<?= Html::img('@web/images/no-image-selected.jpg', ['class' => 'selected-image product-image', 'id' => 'no-image-selected']); ?>
	</div>

	<?= $form->field($model, 'quantity')->textInput() ?>

	<?= $form->field($model, 'utility')->textInput(['value' => is_numeric($model->utility) ? Yii::$app->formatter->asDecimal($model->utility, 2) : null]) ?>

	<?= $form->field($model, 'price')->textInput(['value' => is_numeric($model->price) ? Yii::$app->formatter->asDecimal($model->price, 2) : null]) ?>

	<?= $form->field($model, 'currency')->dropDownList($currencies) ?>

	<?= $form->field($model, 'variant_price')->textInput(['value' => is_numeric($model->variant_price) ? Yii::$app->formatter->asDecimal($model->variant_price, 2) : null]) ?>

	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
