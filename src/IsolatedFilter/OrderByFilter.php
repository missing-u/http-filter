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

    public $order_column_name = 'id';

    public $order_direction = 'desc';

    public $order_table = null;

    public function order_by()
    {
        $table = $this->order_table ?? $this->getTable();

        $this->builder->orderBy(
            $table . '.' . $this->order_column_name,
            $this->order_direction
        );
    }

}