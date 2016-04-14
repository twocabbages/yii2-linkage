<?php

namespace cabbage\linkage;


/**
 * Class Module
 * @package cabbage\linkage
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'cabbage\linkage\controllers';

    /**
     * 搜索所使用的数据表控制类
     * 数据表必须包含三个字段
     * /-- id,name,parent_id --/
     * /-- 1, 父亲, 0        --/
     * /-- 2, 儿子, 1        --/
     * @var string
     */
    public $searchModel = 'cabbage\linkage\models\Region';
}
