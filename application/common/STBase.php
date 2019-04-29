<?php

namespace app\common;
/**
 * 状态基类.
 *
 * @farwish
 */
abstract class STBase
{
	protected static $val = [];
	protected static $val2 = [];

    /** 
     * 获取.
     *
     * @return string | array
     */
    public static function get($key = null)
    {   
        return (isset($key) && isset(static::$val[$key])) ? static::$val[$key] : '';
    }

    /**
     * 获取.
     *
     * @return string | array
     */
    public static function get2($key = null)
    {
        return (isset($key) && isset(static::$val2[$key])) ? static::$val2[$key] : '';
    }
}
