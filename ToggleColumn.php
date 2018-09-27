<?php
namespace hbhe\grid;

use Closure;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\web\View;
use Yii;

/**
 * @author hbhe <57620133@qq.com>
 */
class ToggleColumn extends DataColumn
{
    /**
     * Toggle action that will be used as the toggle action in your controller
     * @var string
     */
    public $action = 'toggle';


    /**
     * @var string pk field name
     */
    public $primaryKey = 'primaryKey';

    /**
     * Whether to use ajax or not
     * @var bool
     */
    public $enableAjax = true;

    /**
     * @var string glyphicon for 'on' value
     */
    public $iconOn = 'ok';

    /**
     * @var string glyphicon for 'off' value
     */
    public $iconOff = 'remove';

    /**
     * @var string text to display on the 'on' link
     */
    public $onText;

    /**
     * @var string text to display on the 'off' link
     */
    public $offText;
    
    /**
     * @var string text to display next to the 'on' link
     */
    public $displayValueText = false;
    
    /**
     * @var string text to display next to the 'on' link
     */
    public $onValueText;
    
    /**
     * @var string text to display next to the 'off' link
     */
    public $offValueText;
    

    public function init()
    {
        if ($this->onText === null) {
            $this->onText = Yii::t('app', 'On');
        }
        if ($this->offText === null) {
            $this->offText = Yii::t('app', 'Off');
        }
        if ($this->onValueText === null) {
            $this->onValueText = Yii::t('app', 'Active');
        }
        if ($this->offValueText === null) {
            $this->offValueText = Yii::t('app', 'Inactive');
        }
        if ($this->enableAjax) {
            $this->registerJs();
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $url = [$this->action, 'id' => $model->{$this->primaryKey}];

        $attribute = $this->attribute;
        $value = $model->$attribute;

        if ($value === null || $value == true) {
            $icon = $this->iconOn;
            $title = $this->offText;
            $valueText = $this->onValueText;
        } else {
            $icon = $this->iconOff;
            $title = $this->onText;
            $valueText = $this->offValueText;
        }
        if ($this->confirm === null) {
            return Html::a(
                    '<span class="glyphicon glyphicon-' . $icon . '"></span>',
                    $url,
                    [
                        'title' => $title,
                        'class' => 'toggle-column',
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        //'data-confirm' => $model->nickname . $title,
                    ]
                ) . ($this->displayValueText ? " {$valueText}" : "");
        } else {
            return Html::a(
                    '<span class="glyphicon glyphicon-' . $icon . '"></span>',
                    $url,
                    [
                        'title' => $title,
                        'class' => 'toggle-column',
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        'data-confirm' => $this->getConfirm($model),
                    ]
                ) . ($this->displayValueText ? " {$valueText}" : "");
        }
    }

    /**
     * Registers the ajax JS
     */
    public function registerJs()
    {
        if(Yii::$app->request->isAjax) {
            return;
        }
        $js = <<<'JS'
$(document.body).on("click", "a.toggle-column", function(e) {
    e.preventDefault();
    $.post($(this).attr("href"), function(data) {
        var pjaxId = $(e.target).closest("[data-pjax-container]").attr("id");
        $.pjax.reload({container:"#" + pjaxId});
    });
    return false;
});
JS;
        $this->grid->view->registerJs($js, View::POS_READY, 'pheme-toggle-column');
    }

    public $confirm;

    protected function getConfirm($model)
    {
        if ($this->confirm instanceof Closure || is_array($this->confirm) && is_callable($this->confirm)) {
            return call_user_func($this->confirm, $model, $this);
        }
        return $this->confirm;
    }
}
