<?php
/**
 * RPC服务配置.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Config;

/**
 * 服务配置.
 */
class PHPClient
{
    public $rpc_secret_key = '769af463a39f077a0340a189e9c1ec28';

    const USER = 'ApiBase';
    const SECRET = '1BA09530-F9E6-478D-9965-7EB31A59537E';
    const RECV_TIME_OUT = 3;
    const CONNECTION_TIME_OUT = 5;

    public $phptools = array(
        'uri' => "#{ServiceBase.rpc.host}",
        'user' => 'ApiBase',
        'secret' => "1BA09530-F9E6-478D-9965-7EB31A59537E",
        'service' => 'ServiceBase'
    );

}
