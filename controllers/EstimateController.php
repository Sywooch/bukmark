<?php

namespace app\controllers;

use Yii;
use app\models\Estimate;
use app\models\EstimateSearch;
use app\models\EstimateEntry;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use yii\helpers\Json;
use yii\helpers\Url;

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
		Estimate::updateSentEstimates();
		$searchModel = new EstimateSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

		if (Yii::$app->request->post('hasEditable')) {
			$id = Yii::$app->request->post('editableKey');
			$model = Estimate::findOne($id);
			$model->scenario = Estimate::SCENARIO_GRID;

			$out = Json::encode(['output' => '', 'message' => '']);

			$post = [];
			$posted = current($_POST['Estimate']);
			$post['Estimate'] = $posted;

			if ($model->load($post)) {
				$model->save();
				$output = '';
				if (isset($posted['status'])) {
					$output = $model->statusLabel;
				}
				$out = Json::encode(['output' => $output, 'message' => $model->getErrors()]);
			}
			// return ajax json encoded response and exit
			echo $out;
			return;
		}

		return $this->render('index', [
					'searchModel' => $searchModel,
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
		$dataProvider->sort = ['defaultOrder' => ['rank' => SORT_ASC]];
		// Set last page as default
		$pagination = $dataProvider->getPagination();
		$page = Yii::$app->request->getQueryParam($pagination->pageParam);
		if (!$page) {
			$pagination->totalCount = $dataProvider->getTotalCount();
			$page = $pagination->getPageCount() - 1;
			$pagination->setPage($page);
		}
		
		if (Yii::$app->request->post('hasEditable')) {
			$id = Yii::$app->request->post('editableKey');
			$entry = EstimateEntry::findOne($id);

			$out = Json::encode(['output' => '', 'message' => '']);

			$post = [];
			$posted = current($_POST['EstimateEntry']);
			$post['EstimateEntry'] = $posted;

			if ($entry->load($post)) {
				if ($entry->save()) {
					$entry->estimate->doEstimate();
				}
				$output = '';
				if (isset($posted['status'])) {
					$output = $entry->statusLabel;
				}
				$out = Json::encode(['output' => $output, 'message' => $entry->getErrors()]);
			}
			// return ajax json encoded response and exit
			echo $out;
			return;
		}
		
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
		$attributes = Yii::$app->session->get(Estimate::className());
		if ($attributes) {
			$model->attributes = $attributes;
		}

		if ($model->load(Yii::$app->request->post())) {
			if (Yii::$app->request->getBodyParam('action')) {
				Yii::$app->session->set(Estimate::className(), $model->attributes);
				return $this->redirect(["client/create"]);
			} else {
				Yii::$app->session->remove(Estimate::className());
			}
			if ($model->save()) {
				return $this->redirect(['index']);
			}
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
	 * @param integer $id estimate id
	 * @return mixed
	 */
	public function actionCreateEntry($id) {
		$estimate = $this->findModel($id);
		$model = new EstimateEntry();
		$model->estimate_id = $estimate->id;
		$attributes = Yii::$app->session->get(EstimateEntry::className());
		if ($attributes) {
			$model->attributes = $attributes;
		}

		if ($model->load(Yii::$app->request->post())) {
			if (Yii::$app->request->getBodyParam('action')) {
				Yii::$app->session->set(EstimateEntry::className(), $model->attributes);
				return $this->redirect(["product/create"]);
			} else {
				Yii::$app->session->remove(EstimateEntry::className());
			}
			if ($model->save()) {
				$estimate->doEstimate();
				return $this->redirect(['view', 'id' => $estimate->id]);
			}
		}
		
		return $this->render('create-entry', [
					'model' => $model,
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
		
		return $this->render('update-entry', [
					'model' => $model,
		]);
	}
	
	/**
	 * Duplicates an existing EstimateEntry model.
	 * If duplication is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDuplicateEntry($id) {
		$model = $this->findEntryModel($id);
		$estimate = $model->estimate;
		
		$duplicate = new EstimateEntry;
		$duplicate->attributes = $model->attributes;
		$duplicate->save();
		$estimate->doEstimate();
		
		if (Yii::$app->getRequest()->isAjax) {
			Yii::$app->getResponse()->getHeaders()->set('Location', Url::to(['view', 'id' => $estimate->id, 'page' => Yii::$app->request->getQueryParam('page', null)]));
			return;
		}
		
		return $this->redirect(['view', 'id' => $estimate->id]);
	}
	
	/**
	 * Reorders an existing EstimateEntry model.
	 * If the operation is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @param booelan $up
	 * @return mixed
	 */
	public function actionReorderEntry($id, $up) {
		$model = $this->findEntryModel($id);
		$estimate = $model->estimate;
		$key = null;
		
		$entries = $estimate->entries;
		foreach ($entries as $k => $entry) {
			if ($entry->rank == null) {
				$entry->rank = $entry->id;
				$entry->save();
			}
			if ($entry->id == $model->id) {
				$key = $k;
			}
		}
		
		if ($key !== null) {
			$key += $up ? -1 : 1;
			if(isset($entries[$key])) {
				$entry = $entries[$key];
				$oldRank = $model->rank;
				$model->rank = $entry->rank;
				$entry->rank = $oldRank;
				$model->save();
				$entry->save();
			}
		}
		
		if (Yii::$app->getRequest()->isAjax) {
			Yii::$app->getResponse()->getHeaders()->set('Location', Url::to(['view', 'id' => $estimate->id, 'page' => Yii::$app->request->getQueryParam('page', null)]));
			return;
		}
		
		return $this->redirect(['view', 'id' => $estimate->id]);
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
		
		if (Yii::$app->getRequest()->isAjax) {
			Yii::$app->getResponse()->getHeaders()->set('Location', Url::to(['view', 'id' => $estimate->id, 'page' => Yii::$app->request->getQueryParam('page', null)]));
			return;
		}
		
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
		if ($estimate->canBeConfirmed()) {
			$model->checked = $check;
			$model->save();
			if ($estimate->status_was_confirmed == false && $check == true) {
				$estimate->setStatusToConfirmed();
			}
			$estimate->doEstimate();
		}

		if (Yii::$app->getRequest()->isAjax) {
			Yii::$app->getResponse()->getHeaders()->set('Location', Url::to(['view', 'id' => $estimate->id, 'page' => Yii::$app->request->getQueryParam('page', null)]));
			return;
		}
		
		return $this->redirect(['view', 'id' => $estimate->id]);
	}
	
	/**
	 * Sets the sample_delivered field of an EstimateEntry model.
	 * If operation is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @param boolean $check
	 * @return mixed
	 */
	public function actionCheckEntrySample($id, $check) {
		$model = $this->findEntryModel($id);
		$estimate = $model->estimate;
		$model->sample_delivered = $check;
		$model->save();

		if (Yii::$app->getRequest()->isAjax) {
			Yii::$app->getResponse()->getHeaders()->set('Location', Url::to(['view', 'id' => $estimate->id, 'page' => Yii::$app->request->getQueryParam('page', null)]));
			return;
		}
		
		return $this->redirect(['view', 'id' => $estimate->id]);
	}
	
	/**
	 * Get the PDF version of the estimate.
	 * @param integer $id estimate ID
	 * @return mixed
	 */
	public function actionGetPdf($id) {
		$estimate = $this->findModel($id);
		$header = $this->renderPartial('pdf-header', ['estimate' => $estimate]);
		$content = $this->renderPartial('pdf', ['estimate' => $estimate]);
		$footer = $this->renderPartial('pdf-footer');

		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_UTF8,
			'format' => Pdf::FORMAT_A4,
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginLeft' => 0,
			'marginRight' => 0,
			'marginTop' => 0,
			'marginBottom' => 0,
			'marginHeader' => 0,
			'marginFooter' => 0,
			'destination' => Pdf::DEST_DOWNLOAD,
			'content' => $content,
			'cssFile' => '@webroot/css/pdf.css',
			'options' => [
				'autoPageBreak' => false,
				'setAutoTopMargin' => 'pad',
				'setAutoBottomMargin' => 'pad',
			],
			'methods' => [
				'SetHTMLHeader' => $header,
				'SetHTMLFooter' => $footer,
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
