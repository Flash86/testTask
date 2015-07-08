<?php
/**
 * Created by PhpStorm.
 * User: zemfr
 * Date: 04.07.15
 * Time: 16:39
 */

namespace Acme\Ecommerce\Helpers;


class ResponseHelp
{
    static function getSuccesMessage($data = null)
    {
        return array(
            'status' => 'ok',
            'data' => $data
        );
    }

    static function getErrorMessage($message)
    {
        return array(
            'status' => 'error',
            'message' => $message
        );
    }


}