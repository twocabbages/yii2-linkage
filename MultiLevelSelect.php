<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-widgets
 * @version 1.0.0
 */

namespace cabbage\linkage;

use kartik\select2\ThemeDefaultAsset;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Select2 widget is a Yii2 wrapper for the Select2 jQuery plugin. This
 * input widget is a jQuery based replacement for select boxes. It supports
 * searching, remote data sets, and infinite scrolling of results. The widget
 * is specially styled for Bootstrap 3.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 * @see http://ivaynberg.github.com/select2/
 */
class MultiLevelSelect extends InputWidget
{

    const LARGE = 'lg';
    const MEDIUM = 'md';
    const SMALL = 'sm';

    /**
     * @var string the locale ID (e.g. 'fr', 'de') for the language to be used by the Select2 Widget.
     * If this property not set, then the current application language will be used.
     */
    public $language = "zh-CN";

    /**
     * @var array addon to prepend or append to the Select2 widget
     * - prepend: array the prepend addon configuration
     *     - content: string the prepend addon content
     *     - asButton: boolean whether the addon is a button or button group. Defaults to false.
     * - append: array the append addon configuration
     *     - content: string the append addon content
     *     - asButton: boolean whether the addon is a button or button group. Defaults to false.
     * - groupOptions: array HTML options for the input group
     * - contentBefore: string content placed before addon
     * - contentAfter: string content placed after addon
     */
    public $addon = [];

    /**
     * @var string Size of the Select2 input, must be one of the
     * [[LARGE]], [[MEDIUM]] or [[SMALL]]. Defaults to [[MEDIUM]]
     */
    public $size = self::MEDIUM;

    /**
     * @var array $data the option data items. The array keys are option values, and the array values
     * are the corresponding option labels. The array can also be nested (i.e. some array values are arrays too).
     * For each sub-array, an option group will be generated whose label is the key associated with the sub-array.
     * If you have a list of data models, you may convert them into the format described above using
     * [[\yii\helpers\ArrayHelper::map()]].
     */
    public $data;

    public $dataProvider;

    public $defaultData;

    public $url = "/linkage/default/select";
    /**
     * @var array the HTML attributes for the input tag. The following options are important:
     * - multiple: boolean whether multiple or single item should be selected. Defaults to false.
     * - placeholder: string placeholder for the select item.
     */
    public $options = [];

    /**
     * @var boolean indicator for displaying text inputs
     * instead of select fields
     */
    private $_hidden = false;

    public $parent_id = 1;

    public $level = 0;

    /**
     * Initializes the widget
     *
     * @throw InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->dataProvider) {
            $this->dataProvider = Yii::$app->getModule("linkage")->searchModel;
        }
        if (!isset($this->data) && !$this->_hidden && $this->dataProvider === null) {
            throw new InvalidConfigException("No 'data' source found for Select2. Either the 'data' property must be set OR one of 'data', 'query', 'ajax', or 'tags' must be set within 'pluginOptions'.");
        }
        if (!empty($this->options['placeholder']) && !$this->_hidden &&
            (empty($this->options['multiple']) || $this->options['multiple'] == false)
        ) {
            $this->data = ["" => ""] + $this->data;
        }

        if ($this->dataProvider) {
            $class = $this->dataProvider;
            $this->data = $this->multiMap($class::find()->where(['parent_id' => $this->parent_id])->asArray()->all(), ['id' => 'id', 'name' => 'text']);
        }

        if ($this->hasModel()) {
            $this->name = ArrayHelper::remove($this->options, 'name', Html::getInputName($this->model, $this->attribute));
            $this->defaultData = $this->model[Html::getAttributeName($this->attribute)];
        }
        if ($this->defaultData) {
            $this->defaultData = $this->getDefaultData($this->defaultData);
        }
        if (!isset($this->options['style'])) {
            $this->options['style'] = 'margin-right:15px; display:inline-block; width: auto;';
        }
        $this->data = Json::encode(['results' => $this->data, 'more' => false]);
        $this->defaultData = Json::encode($this->defaultData);

        $this->registerAssets();
        $this->renderInput();
    }

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

    public function getDefaultData($id)
    {
        $result = null;
        $class = $this->dataProvider;
        if ($model = $class::find()->where(['id' => $id])->one()) {
            if ($model->id === 1) return [];
            $parent = $this->getDefaultData($model->parent_id);
            $result = array_merge($parent ? $parent : [], [$model->id]);
        }
        return $result;
    }

    /**
     * Renders the source Input for the Select2 plugin.
     * Graceful fallback to a normal HTML select dropdown
     * or text input - in case JQuery is not supported by
     * the browser
     */
    protected function renderInput()
    {
        echo Html::input('text', null, null, $this->options);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        Select2Asset::register($view);
        $request_url = Yii::$app->urlManager->createUrl([$this->url]);
        $fieldId = str_replace("-", '_', $this->options['id']);
        $view->registerJs(<<<SCRIPT
var source_data_{$fieldId} = {$this->data};
var default_data_{$fieldId} = {$this->defaultData};
var select_level_{$fieldId} = {$this->level};

function templateResult (e) {
    var _select = $(this);
    var _match = /-level-([\d]*)/.exec(_select.attr('id'));
        var _level = 0;
        if(_match && _match[1]){
            _level = _match[1];
        }
        if(select_level_{$fieldId} !== 0 && _level+1>=select_level_{$fieldId}){
            _select.parent().children('input').last().attr("name", "{$this->name}");
            return  default_data_{$fieldId} = null;
        }
        if(_select.val() == "") return false;
        $.getJSON("{$request_url}&parent_id="+_select.val(), function(data){
            if(data.results.length > 0){
                var _match = /-level-([\d]*)/.exec(_select.attr('id'));
                if(_match){
                    var child_id = _select.attr('id').replace(_match[0], '-level-'+ (parseInt(_match[1])+1));
                }else{
                    var child_id = _select.attr('id') + "-level-1";
                }

                if($("#"+child_id).length < 1){
                    if(_select.parent().find(".help-block").length > 0) {
                        _select.parent().find(".help-block").before('<input id="'+ child_id +'" style="{$this->options['style']}"/>');
                    } else {
                        _select.parent().append('<input id="'+ child_id +'" style="{$this->options['style']}"/>');
                    }
                }
                $("#"+child_id).select2({
                    data:data['results'],
                    width: "auto",
                }).on("change", function() {
                    templateResult.call(this);
                }).val(data.results[0].id);
                init_select2($("#"+child_id), data.results[0].id);
            } else {
                default_data_{$fieldId} = null;
                _select.parent().children('input').last().attr("name", "{$this->name}");
            }
        })
  }

$("#{$this->options['id']}").select2({
    data: source_data_{$fieldId}['results'],
    width: "auto",
    style: "margin-left:5px;",
}).on("change", function() {
    templateResult.call(this);
});


function init_select2( _select, _id ){
    var _match = /-level-([\d]*)/.exec(_select.attr('id'));
    if(_match){
        var child_id = _match[1];
    }else{
        var child_id = 0;
    }
    if( default_data_{$fieldId} !=null && default_data_{$fieldId}[child_id] != undefined && default_data_{$fieldId}[child_id] != null ){
        _select.val(default_data_{$fieldId}[child_id]);
    }else{
        _select.val(_id);
    }
    _select.trigger('change');
}
if($("#{$this->options['id']}").length>0){
    init_select2($("#{$this->options['id']}"), source_data_{$fieldId}.results[0].id);
}
SCRIPT
        );

        $view->registerCss('.select2-container--default {margin-left: 10px; display:inline-block; padding: 0; margin-right: 0;} ');
    }
}
