<?php

namespace app\controllers;

use Yii;
use app\models\Estimate;
use app\models\EstimateEntry;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\ClientSearch;
use app\models\ProductSearch;
use kartik\mpdf\Pdf;

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
					'delete-entry' => ['post'],
					'check-entry' => ['post'],
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
			'query' => Estimate::find()->with('client')->active(),
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
				'query' => $model->getEntries()->with('product.supplier'),
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

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
		}
		
		$searchModel = new ClientSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->pagination->pageSize = 5;
		
		return $this->render('create', [
					'model' => $model,
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Updates an existing Estimate model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
		}
		
		$searchModel = new ClientSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->pagination->pageSize = 5;
		
		return $this->render('update', [
					'model' => $model,
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
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
	 * Creates a new EstimateEntry model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id estimate id
	 * @return mixed
	 */
	public function actionCreateEntry($id) {
		$estimate = $this->findModel($id);
		$model = new EstimateEntry();
		$model->estimate_id = $estimate->id;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
				$estimate->doEstimate();
				return $this->redirect(['view', 'id' => $estimate->id]);
		}
		
		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->pagination->pageSize = 5;
		
		return $this->render('create-entry', [
					'model' => $model,
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Updates an existing EstimateEntry model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdateEntry($id) {
		$model = $this->findEntryModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
				$model->estimate->doEstimate();
				return $this->redirect(['view', 'id' => $model->estimate->id]);
		}
		
		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->pagination->pageSize = 5;
		
		return $this->render('update-entry', [
					'model' => $model,
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
		]);
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
	 * Get the PDF version of the estimate.
	 * @param integer $id estimate ID
	 * @return mixed
	 */
	public function actionGetPdf($id) {
		$estimate = $this->findModel($id);
		$content = $this->renderPartial('pdf');

		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'format' => Pdf::FORMAT_A4,
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'destination' => Pdf::DEST_DOWNLOAD,
			'content' => $content,
			'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
			'methods' => [
				'SetFooter' => ['{PAGENO}'],
			]
		]);

		// return the pdf output as per the destination setting
		return $pdf->render();
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
