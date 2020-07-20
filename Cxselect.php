<?php

namespace sh\cxselect;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;
use common\models\Area;

class Cxselect extends \yii\widgets\InputWidget
{
    /**
     * @var \yii\widgets\ActiveField active input field, which triggers this widget rendering.
     * This field will be automatically filled up in case widget instance is created via [[\yii\widgets\ActiveField::widget()]].
     * @since 2.0.11
     */
    public $field;
    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;
    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;
    /**
     * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
     */
    public $name;
    /**
     * @var string the input value.
     */
    public $value;
    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    public $attributes = ['province','city','area'];

    public $url;
    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $selects = '';
        $id = $this->options['id'];
        $values = $this->defaultValue();
        foreach ($this->attributes as $key => $v) {
            if ($key >= (count($values) - 1)) {
                $name = Html::getInputName($this->model,$this->attribute);
                $this->attributes[$key] = $v;
            }else{
                $name = $v;
            }
            $selects .= Html::tag('select', '',['class'=>$v,'name'=>$name,'data-value'=>isset($values[$key]) ? $values[$key] : '']);
        }
        //输出html
        echo Html::tag('div',$selects,$this->options);

        //注册js
        $view = $this->getView();
        $options = [
            'url' => $this->url,
            'selects' => $this->attributes,
            'emptyStyle'=>'none',
        ];

        CxselectAsset::register($view);
        $js = Json::encode($options);
        $view->registerJs("jQuery('#{$id}').cxSelect({$js})");
    }

    public function defaultValue()
    {
        $attribute = $this->attribute;
        $fieldvalue = $this->model->$attribute;
        if ($fieldvalue) {
            $area = $city = $province = [];
            $area = Area::find()->where(['codeid'=>$fieldvalue])->asArray()->one();
            $city = Area::find()->where(['codeid'=>$area['parentid']])->asArray()->one();
            if ($city && $city['parentid']) {
                $province = Area::find()->where(['codeid'=>$city['parentid']])->asArray()->one();
            }
            if ($province) {
                return [$province['codeid'],$city['codeid'],$area['codeid']];
            }
            return [$city['codeid'],$area['codeid']];
        }
        return false;
    }

}