<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 7/26/18
 * Time: 3:05 PM
 */

namespace HttpFilter\IsolatedFilter;

//这里对简单分页有问题
trait PaginateFilter
{
    protected static $auto_invoked_register_paginate = 'paginate';

    public $size_key = 'size';

    public $page_key = 'page';

    public function paginate()
    {
        $request = $this->request;

        $size_key = $this->size_key;

        $page_key = $this->page_key;

        $size = $request[ $size_key ] ?? 0;

        $page = $request[ $page_key ] ?? 0;

        $page = max(--$page, 0);

        $this->builder->offset($page * $size)->limit($size);

        return $this;
    }

}
