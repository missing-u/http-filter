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
    static $invoke_regardless_request_property_size = "size";

    //每页数量
    protected $per_page_size = 20;

    /**
     * @param int $per_page_size
     */
    public function setPerPageSize(int $per_page_size) : void
    {
        $this->per_page_size = $per_page_size;
    }

    protected $default_page_num = 1;

    public function size()
    {
        //todo
        // 如果直接使用下面的语句 非联表查询的时候会没有问题
        // 但是联表查询(可能)的时候会有问题
        //  $this->builder->getModel()->setPerPage( (int) $this->request->input("size",20));

        //现在的解决方式有缺陷
        $size = $this->request->input("size", $this->per_page_size);
        $page = $this->request->input("page", $this->default_page_num);
        if ($page == 0) {
            $page = 1;
        }
        --$page;

        $this->builder->offset($page * $size)->limit($size);

        return $this;
    }

}
