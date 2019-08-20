<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 10/16/18
 * Time: 5:45 PM
 */

namespace HttpFilter;

use HttpFilter\Exceptions\InValidSilentFilterException;
use ReflectionClass;
use ReflectionException;
use TheSeer\Tokenizer\Exception;
use Throwable;


trait InvokeFilterRegardlessRequestTrait
{
    static $needInvokeFilterMethods = [];

    /**
     * @return array
     */
    public function getNeedInvokeFilterMethods() : array
    {
        return self::$needInvokeFilterMethods;
    }

    /**
     * @param array $needInvokeFilterMethods
     */
    public function setNeedInvokeFilterMethods(
        string $needInvokeFilterMethod
    ) : void {
        self::$needInvokeFilterMethods[] = $needInvokeFilterMethod;
    }

    /**
     * 一个参数不能同时代表两种含义　
     * 换句话说　一个参数　不能同时触发两个函数　
     * 即　　函数名与所接受到的参数名保持一致 就可以保证　函数名的唯一
     * 所以　不需要额外　的类前缀　
     * 因为是　默认触发的　所以　也不需要　额外的传递参数　
     *
     * 类应该保持功能单一　
     * 所以　invoke_regardless_request 去字符串类型　而非　数组类型
     */
    public function generateNeedInvokeFilterMethods()
    {
        try {
            $reflect = new ReflectionClass(static::class);
        } catch (ReflectionException $e) {
            //实际上永远不会允许到这里　
            //暂时没有找到抑制错误的方法　先使用一个　无用的　try catch
        }

        $properties = $reflect->getStaticProperties();

        array_map(function ($property, $val) {

            //trait 不能定义常量 　　属性中定义的　static property 的　前缀　
            $invoke_property_prefix = "invoke_regardless_request_property";

            if (strpos($property, $invoke_property_prefix) === 0) {
                if ( !is_string($val)) {
                    throw new InValidSilentFilterException();
                }
                $this->setNeedInvokeFilterMethods($val);
            }
        }, array_keys($properties), $properties);

//        return $this->getNeedInvokeFilterMethods();
    }

}