<?php

namespace app\controllers;

use Yii;
use app\models\Receipt;
use app\models\ReceiptSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * ReceiptController implements the CRUD actions for Receipt model.
 */
class ReceiptController extends Controller
{
    public function behaviors()
    {
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
     * Lists all Receipt models.
     * @return mixed
     */
    public function actionIndex()
    {
		$searchModel = new ReceiptSearch();
		$searchModel->load(Yii::$app->request->post());
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];
		
		if (Yii::$app->request->post('hasEditable')) {
			$id = Yii::$app->request->post('editableKey');
			$model = Receipt::findOne($id);

			$out = Json::encode(['output' => '', 'message' => '']);

			$post = [];
			$posted = current($_POST['Receipt']);
			$post['Receipt'] = $posted;

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
     * Displays a single Receipt model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Receipt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
	 * @param integer $estimateId
     * @return mixed
     */
    public function actionCreate($estimateId)
    {
		$model = Receipt::findOne(['estimate_id' => $estimateId]);
		
		if($model) {
			return $this->redirect(['view', 'id' => $model->id]);
		}
		
        $model = new Receipt();
		$model->estimate_id = $estimateId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Receipt model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Receipt model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Receipt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Receipt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Receipt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
