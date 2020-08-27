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
    static $auto_invoked_register_order_by = "order_by";

    private $order_column_name = 'id';

    private $order_direction = 'desc';

    private $order_table = null;

    public function order_by()
    {
        $table = $this->order_table ?? $this->getTable();

        $this->builder->orderBy(
            $table . '.' . $this->order_column_name,
            $this->order_direction
        );
    }

    public function setOrderDirection(string $order_direction)
    {
        $this->order_direction = $order_direction;
    }

    public function setOrderColumnName(string $order_column_name)
    {
        $this->order_column_name = $order_column_name;
    }

    /**
     * @param null $order_table
     */
    public function setOrderTable(string $order_table)
    {
        $this->order_table = $order_table;
    }

}