<?php

namespace app\controllers;

use Yii;
use app\models\Estimate;
use app\models\EstimateEntry;
use app\models\Product;
use app\models\Variant;
use app\models\Massbuy;
use app\models\ProductSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Currency;

/**
 * EstimateController implements the CRUD actions for Estimate model.
 */
class EstimateController extends Controller {

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
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
	 * Lists all Estimate models.
	 * @return mixed
	 */
	public function actionIndex() {
		$dataProvider = new ActiveDataProvider([
			'query' => Estimate::find(),
			'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
		]);

		return $this->render('index', [
					'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Estimate model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		$model = $this->findModel($id);
		$dataProvider = new ActiveDataProvider([
				'query' => $model->getEntries(),
		]);
		
		return $this->render('view', [
					'model' => $model,
					'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new Estimate model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Estimate();
		$model->us = Currency::US_TO_ARS + Estimate::US_TO_ARS_MARGIN;
		$model->save();
		return $this->redirect(['view', 'id' => $model->id]);
	}

	public function actionSelectProduct($id) {
		$estimate = $this->findModel($id);

		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('select-product', [
					'estimate' => $estimate,
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
		]);
	}

	public function actionSelectVariant($id, $productId) {
		$estimate = $this->findModel($id);
		$product = $this->findProductModel($productId);
		
		$dataProvider = new ActiveDataProvider([
			'query' => $product->getVariants(),
		]);
		
		return $this->render('select-variant', [
					'estimate' => $estimate,
					'product' => $product,
					'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionAddProduct($id, $productId, $variantId = null) {
		$estimate = $this->findModel($id);
		$product = $this->findProductModel($productId);
		$variant = null;
		if($variantId) {
			$variant = $this->findVariantModel($variantId);
		}
		$model = new EstimateEntry;

		$model->estimate_id = $estimate->id;
		$model->product_id = $product->id;
		if($variant) {
			$model->variant_id = $variant->id;
		}

		$price = $product->price;
		if ($product->currency == Currency::CURRENCY_USD) {
			$price = $product->price * $estimate->us;
		}
		$model->price = $price;
		
		if($variant) {
			$variantPrice = $variant->price;
			if($variant->currency == Currency::CURRENCY_USD) {
				$variantPrice = $variant->price * $estimate->us;
			}
			$model->variant_price = $variantPrice;
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$massbuy = Massbuy::find()->where(['product_id' => $product->id])->andWhere(['<', 'quantity', $model->quantity])->orderBy(['quantity' => SORT_DESC])->one();
			$utilityDrop = 0;
			if($massbuy) {
				$utilityDrop = $massbuy->utility_drop;
			}
			$model->utility = $product->utility - $utilityDrop;
			if($model->save()) {
				$estimate->doEstimate();
				return $this->redirect(['view', 'id' => $estimate->id]);
			}
		}
		return $this->render('add-product', [
					'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Estimate model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}
	
	/**
	 * Deletes an existing EstimateEntry model.
	 * If deletion is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDeleteEntry($id) {
		$model = $this->findEntryModel($id);
		$estimate = $model->estimate;
		$model->delete();
		$estimate->doEstimate();

		return $this->redirect(['view', 'id' => $estimate->id]);
	}
	
	/**
	 * Sets the checked field of an EstimateEntry model.
	 * If operation is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @param boolean $check
	 * @return mixed
	 */
	public function actionCheckEntry($id, $check) {
		$model = $this->findEntryModel($id);
		$estimate = $model->estimate;
		$model->checked = $check;
		$model->save();
		$estimate->doEstimate();

		return $this->redirect(['view', 'id' => $estimate->id]);
	}

	/**
	 * Finds the Estimate model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Estimate the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Estimate::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * Finds the Product model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Product the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findProductModel($id) {
		if (($model = Product::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * Finds the Variant model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Variant the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findVariantModel($id) {
		if (($model = Variant::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	/**
	 * Finds the EstimateEntry model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return EstimateEntry the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findEntryModel($id) {
		if (($model = EstimateEntry::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
