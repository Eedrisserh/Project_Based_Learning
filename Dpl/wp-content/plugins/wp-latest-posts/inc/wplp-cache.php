<?php

/**
 * Class WPLPCache
 */
class WPLPCache
{
    /**
     * Retrieves cached data.
     *
     * @param integer $key Key
     *
     * @return mixed
     */
    public static function get($key)
    {
        return get_transient($key);
    }

    /**
     * Retrieves cached data.
     *
     * @param null    $key       Key
     * @param array   $data      Data
     * @param integer $timeValue Time value
     * @param string  $timeUnit  Time unit
     *
     * @return boolean
     */
    public static function set($key = null, $data = array(), $timeValue = 1, $timeUnit = 'minute')
    {
        if (!$key) {
            return false;
        }

        if (false === filter_var($timeValue, FILTER_VALIDATE_INT) || $timeValue <= 0) {
            $timeValue = 1;
        }

        switch ($timeUnit) {
            case 'minute':
                $time = 60;
                break;

            case 'hour':
                $time = 60 * 60;
                break;

            case 'day':
                $time = 60 * 60 * 24;
                break;

            case 'week':
                $time = 60 * 60 * 24 * 7;
                break;

            case 'month':
                $time = 60 * 60 * 24 * 30;
                break;

            case 'year':
                $time = 60 * 60 * 24 * 365;
                break;

            default:
                $time = 60;
                break;
        }

        $expiration = $time * $timeValue;

        // Store transient
        set_transient($key, $data, $expiration);
    }
}
