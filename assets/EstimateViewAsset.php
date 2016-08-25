<?php

namespace app\assets;

use yii\web\AssetBundle;

class EstimateViewAsset extends AssetBundle {

	public $basePath = '@webroot';
	public $baseUrl = '@web';
	
	public $js = [
		'js/estimate-view.js',
	];
	
	public $depends = [
        'app\assets\AppAsset',
    ];
}
