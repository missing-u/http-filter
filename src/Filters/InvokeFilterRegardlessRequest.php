<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 10/16/18
 * Time: 5:45 PM
 */

namespace App\Modules\Filters;

use App\Modules\Exceptions\SeriousException;
use ReflectionClass;


/**
 *
 * 有些过滤器　和是否传递参数无关　
 * 比如　分页　　当不传递　page　参数时
 * 也需要　添加过滤器　 page=默认值　(1)
 *
 * 实现方式
 *
 * 获取到所有　trait
 *
 * 将　trait 中的　方法　
 *
 */
trait InvokeFilterRegardlessRequest
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

//    public function InvokeFilterRegardlessRequest()
//    {
//
//    }

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
//    public function generateNeedInvokeFilterMethods()
//    {
//        $register_traits = get_declared_traits();
//
//        array_map(function ($trait) {
//            if (isset($trait::$invoke_regardless_request)) {
//                self::setNeedInvokeFilterMethods($trait::$invoke_regardless_request);
//            }
//        }, $register_traits);
//
////        return $this->getNeedInvokeFilterMethods();
//    }


    /**
     * @throws \ReflectionException
     */
    public function generateNeedInvokeFilterMethods()
    {
        $reflect = new ReflectionClass(static::class);

        $properties = $reflect->getStaticProperties();


//        dd($properties);
        array_map(function ($property, $val) {

            //trait 不能定义常量 　　属性中定义的　static property 的　前缀　
            $invoke_property_prefix = "invoke_regardless_request_property";


            if (strpos($property, $invoke_property_prefix) === 0) {
                if ( !is_string($val)) {
                    throw new SeriousException(
                        "默认触发的　http 过滤器　的函数名 必须是字符串
                    定义的属性　以　invoke_regardless_request_property　命名"
                    );
                }
                $this->setNeedInvokeFilterMethods($val);
            }
        }, array_keys($properties), $properties);

//        return $this->getNeedInvokeFilterMethods();
    }

}