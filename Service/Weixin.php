<?php
namespace Service;

class Weixin extends \Service\ServiceBaseBackend
{
    protected static $className = 'Weixin';

    /**
     * Get Instance.
     *
     * @param boolean $sington 是否单例.
     *
     * @return \Service\Weixin
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

}