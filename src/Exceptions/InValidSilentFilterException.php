<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 8/20/19
 * Time: 9:22 AM
 */

namespace HttpFilter\Exceptions;


use Throwable;

class InValidSilentFilterException extends BaseHttpFilterException
{
    public function __construct()
    {
        $message = "类型错误　invoke_regardless_request_property　为前缀的静态属性必须为字符串(此前缀的属性为默认触发的http过滤器)";

        parent::__construct($message);
    }
}