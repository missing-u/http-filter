<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 10/8/18
 * Time: 11:32 AM
 */

namespace HttpFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class HttpFilter
 * @package App\Http\Filters
 */
class Filter implements Scope
{
    use InvokeFilterRegardlessRequestTrait;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var $model Model
     */
    protected $model;

    protected $request;

    protected $default_filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     * @throws \ReflectionException
     */
    public function apply(Builder $builder, Model $model)
    {
        $this->model = $model;

        $this->builder = $builder;

        $filter_params = $this->get_filter_params();

        array_map(function ($val, $name) {

            if (method_exists($this, $name)) {
                call_user_func([$this, $name], $val);
            }

        }, $filter_params, array_keys($filter_params));

        $keys_in_request = $this->request->keys();

        $this->generateNeedInvokeFilterMethods();

        $default_invoke_methods = $this->getNeedInvokeFilterMethods();

        $diff_invoke_methods = array_diff($default_invoke_methods, $keys_in_request);

        array_map(function ($name) {
            call_user_func([$this, $name]);
        }, $diff_invoke_methods);

        return $this->builder;
    }

    public function get_filter_params()
    {
        return $this->request->all();
    }

    public function getTable()
    {
        return $this->model->getTable();
    }

}
