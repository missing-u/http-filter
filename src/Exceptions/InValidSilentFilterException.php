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
        $message = "默认触发的　http 过滤器　的函数名 必须是字符串
                    定义的属性　以　invoke_regardless_request_property　命名";

        parent::__construct($message);
    }
}