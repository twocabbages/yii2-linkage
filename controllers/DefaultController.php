<?php

namespace cabbage\linkage\controllers;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Class DefaultController
 * @package cabbage\linkage\controllers
 */
class DefaultController extends Controller
{
    /**
     * 主页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'defaultData' => \Yii::$app->request->post('User') ? \Yii::$app->request->post('User')['region_id'] : null,
        ]);
    }

    /**
     * 查询自地区
     * @param int $parent_id
     */
    public function actionSelect($parent_id = 1)
    {
        $className = $this->action->controller->module->searchModel;
        $results = $this->multiMap($className::find()->where(['parent_id' => $parent_id])->asArray()->all(), ['id' => 'id', 'name' => 'text']);
        echo Json::encode(['results' => $results, 'more' => false]);
    }

    /**
     * 格式化地区查询结果
     * @param $array
     * @param $map
     * @return array
     */
    public function multiMap($array, $map)
    {
        $result = [];

        foreach ($array as $k => $v) {
            foreach ($map as $key => $value) {
                $result[$k][$value] = ArrayHelper::getValue($v, $key);
            }
        }
        return $result;
    }
}
