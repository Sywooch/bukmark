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
		return $this->render('create', [
					'model' => $model,
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
		return $this->render('update', [
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
	 * Creates a new EstimateEntry model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @param integet $id estimate id
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
		return $this->render('create-entry', [
					'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing EstimateEntry model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integet $id
	 * @return mixed
	 */
	public function actionUpdateEntry($id) {
		$model = $this->findEntryModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
				$model->estimate->doEstimate();
				return $this->redirect(['view', 'id' => $model->estimate->id]);
		}
		return $this->render('update-entry', [
					'model' => $model,
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
