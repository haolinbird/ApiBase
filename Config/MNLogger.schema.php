<?php

namespace Config;

class MNLogger
{

    public $exception2 = array(
        'on' => true,
        'app' => JM_APP_NAME,
        'logdir' => "#{ServiceBase.Log.MNLogger}",
    );
    public $trace2 = array(
        'on' => true,
        'app' => JM_APP_NAME,
        'logdir' => "#{ServiceBase.Log.MNLogger}",
    );
    public $data2 = array(
        'on' => true,
        'app' => JM_APP_NAME,
        'logdir' => "#{ServiceBase.Log.MNLogger}",
    );
}
