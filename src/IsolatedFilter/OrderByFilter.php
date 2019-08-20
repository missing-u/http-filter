<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 10/16/18
 * Time: 3:24 PM
 */

namespace HttpFilter\IsolatedFilter;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait OrderByFilter
 * @package HttpFilter\IsolatedFilter
 */
trait OrderByFilter
{
    static $invoke_regardless_request_property_order_by = "order_by";

    // 避免重名

    protected $order_column_name = "id";

    protected $return_row_order = "desc";

    /**
     * @param string $return_row_order
     */
    public function setReturnRowOrder(string $return_row_order) : void
    {
        $this->return_row_order = $return_row_order;
    }

    /**
     * @param string $order_column_name
     */
    public function setOrderColumnName(string $order_column_name) : void
    {
        $this->order_column_name = $order_column_name;
    }

    public function order_by()
    {
        /**
         * @var $builder Builder
         */
        $builder = $this->builder;

        return
            $builder->orderBy(
                $this->getTable() . "." . $this->order_column_name,
                $this->return_row_order
            );
    }
}