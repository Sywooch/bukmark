<?php

namespace app\assets;

use yii\web\AssetBundle;

class EstimateEntryAsset extends AssetBundle {

	public $basePath = '@webroot';
	public $baseUrl = '@web';
	
	public $js = [
		'js/estimate-entry.js',
	];
	
	public $css = [
		'css/estimate-entry.css',
	];
	
	 public $depends = [
        'app\assets\AppAsset',
    ];
}
