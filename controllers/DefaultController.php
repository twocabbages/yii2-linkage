<?php

namespace cabbage\linkage\controllers;

use cabbage\linkage\models\Region;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index',[
            'defaultData' => \Yii::$app->request->post('User') ? \Yii::$app->request->post('User')['region_id'] : null,
        ]);
    }

    public function actionSelect( $parent_id = 1 ){
        $results = $this->multiMap(Region::find()->where(['parent_id'=>$parent_id])->asArray()->all(), ['id'=>'id', 'name'=>'text']);
        echo Json::encode(['results'=>$results, 'more'=>false]);
    }

    public function multiMap( $array, $map ){
        $result = [];

        foreach($array as $k => $v){
            foreach($map as $key => $value){
                $result[$k][$value] = ArrayHelper::getValue($v, $key);
            }
        }
        return $result;
    }
}
