<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 10/8/18
 * Time: 11:32 AM
 */

namespace App\Modules\Filters;

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
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var $model Model
     */
    protected $model;


    use InvokeFilterRegardlessRequest;

    protected $request;

    protected $default_filters = [];


    public function getTable()
    {
        return $this->model->getTable();
    }

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setDefaultFilters($filter) : void
    {
        if (is_string($filter)) {
            $filter = [$filter];
        }
        $this->default_filters += $filter;
    }


    public function apply(Builder $builder, Model $model)
    {
        $this->model = $model;

        $this->builder = $builder;

        foreach ($this->request->all() as $name => $val) {
            if (method_exists($this, $name)
            ) {
                if ($this->request->has($name)) {
                    call_user_func([$this, $name], $val);
                }
            }
        }

        $keys_in_request = $this->request->keys();

        $this->generateNeedInvokeFilterMethods();

        $default_invoke_methods = $this->getNeedInvokeFilterMethods();

        $default_invoke_methods = array_diff($default_invoke_methods,
            $keys_in_request);

        array_map(function ($name) {
            call_user_func([$this, $name]);
        }, $default_invoke_methods);

        return $this->builder;
    }


}
