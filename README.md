Toggle data column for Yii2
===========================
Provides a toggle data column and action

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist hbhe/yii2-toggle-column "*"
```

or add

```
"hbhe/yii2-toggle-column": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
// Controller文件中
use hbhe\grid\actions\ToggleAction;

public function actions()
{
    return [
        'toggle-status' => [
            'class' => ToggleAction::className(),
            'onValue' => User::STATUS_ACTIVE,
            'offValue' => User::STATUS_NOT_ACTIVE,
            'modelClass' => 'common\models\User',
            'attribute' => 'status',
            // Uncomment to enable flash messages
            'setFlash' => true,
        ]
    ];
}

// View文件中
// Pjax::begin();
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        [
            'class' => '\hbhe\grid\ToggleColumn',
            'attribute' => 'status',
            'action' => 'toggle-status',
            'onText' => '禁用',
            'offText' => '启用',
            'displayValueText' => true,
            'onValueText' => '已禁用',
            'offValueText' => '已启用',
            'iconOn' => 'stop',
            'iconOff' => 'stop',
            'enableAjax' => false, // 使用pjax时要设为true
            'confirm' => function($model, $toggle) {
                if ($model->status == Member::STATUS_NOT_ACTIVE) {
                    return "确认启用: {$model->username}({$model->id})?";
                } else {
                    return "确认禁用: {$model->username}({$model->id})?";
                }
            },
            'headerOptions' => array('style' => 'width:80px;'),
        ],
    ],
]);

// Pjax::end();
```

 ![截图](https://github.com/hbhe/yii2-toggle-column/blob/master/screenshot.png)