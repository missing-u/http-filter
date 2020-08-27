<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 10/8/18
 * Time: 11:32 AM
 */

namespace HttpFilter;

use HttpFilter\Exceptions\InValidSilentFilterException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Scope;
use ReflectionClass;

/**
 * Class HttpFilter
 * @package App\Http\Filters
 */
class Filter implements Scope
{
    protected $filter_param_space = true;

    protected $builder;

    protected $model;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 当调用 model::addGlobeScope($filter) 的时候,会执行到这里
     * @param Builder $builder
     * @param Model $model
     * @return void
     * @throws Exception
     */
    public function apply(Builder $builder, Model $model)
    {
        // 对参数初始化赋值
        {
            $this->model = $model;

            $this->builder = $builder;

            $filter_params = $this->request->all();

            $keys_in_request = array_keys($filter_params);
        }

        // 触发自定义函数定义的筛选条件
        {
            array_map(function ($val, $name) {
                if (
                    //哪怕 name 是恶意的 __construct 或者 apply  等 也不会造成影响
                method_exists($this, $name)
                ) {
                    if ($this->filter_param_space) {
                        $val = trim($val);
                    }
                    $this->$name($val);
                }
            }, $filter_params, $keys_in_request);
        }

        // 触发 以 auto_invoked_register_ 为前缀所注册的属性的函数的筛选条件
        {
            $default_invoke_methods = $this->getAutoInvokedProperties();

            $diff_invoke_methods = array_diff($default_invoke_methods, $keys_in_request);

            array_map(function ($name) {
                $this->$name();
            }, $diff_invoke_methods);
        }
    }

    public function getAutoInvokedProperties() : array
    {
        $properties = (new ReflectionClass(static::class))
            ->getStaticProperties();

        $final = [];

        array_map(function ($property, $val) use (&$final) {
            //trait 不能定义常量 　　属性中定义的　static property 的　前缀　
            $invoke_property_prefix = 'auto_invoked_register_';

            if (strpos($property, $invoke_property_prefix) === 0) {
                if ( !is_string($val)) {
                    throw new InValidSilentFilterException();
                }

                $final[] = $val;
            }
        }, array_keys($properties), $properties);

        return $final;
    }

    public function getTable()
    {
        return $this->model->getTable();
    }

}
