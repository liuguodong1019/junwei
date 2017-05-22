<?php
return array( // 添加下面一行定义即可
    'app_init' => array(
        'Common\Behavior\InitHookBehavior',
    ),
    'app_begin' => array(
        'Behavior\CheckLangBehavior',
        'Behavior\CronRunBehavior',
        'Common\Behavior\UrldecodeGetBehavior'
    ),
    'view_filter' => array(
        'Common\Behavior\TmplStripSpaceBehavior'
    ),
    'admin_begin' => array(
        'Common\Behavior\AdminDefaultLangBehavior'
    ),
//    'app_end' => array('Behavior\CronRunBehavior'),
)
;