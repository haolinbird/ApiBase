<?php
// 引入公用(跨项目)类库加载器.
// 引入配置文件
require_once(__DIR__.'/../Config.inc.php');
require JM_VENDOR_DIR.'Bootstrap/Autoloader.php';
Bootstrap\Autoloader::instance()->init();

/**
 * Docker的健康监测.
 *
 */
class Health
{

    /**
     * 返回http的code=200表示成功, 500表示异常.
     */
    public function process()
    {
        try {
            $this
                ->checkDove()
                ->checkRedis()
                ->checkRpc();
        } catch (\Exception $e) {
            header('http/1.0 500');
        }
    }


    protected function checkDove()
    {
        $env = \Config\Env::$env;
        if (!ctype_digit((string)$env)) {
            throw new \Exception('dove error');
        }
        return $this;
    }

    protected function checkRpc()
    {
        \PHPClient\Text::inst('ServiceBase')->setClass('Demo')->health(['time' => time()]);
        return $this;
    }

    protected function checkRedis()
    {
        $redis = \Redis\RedisMultiStorage::getInstance('default');
        $key = uniqid();
        $value = time();
        $redis->set($key, $value);
        $res = $redis->get($key);
        if ($value != $res) {
            throw new \Exception('redis error');
        }
        $redis->del($key);
        return $this;
    }
}
$o = new Health();
$o->process();