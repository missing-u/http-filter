<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 10/16/18
 * Time: 3:30 PM
 */

namespace HttpFilter\IsolatedFilter;

trait TimePeriod
{
    protected static $auto_invoked_register_create_time = 'create_time';

    public $time_column_name = 'created_at';

    // 传递过来的参数 代表结束的时间戳的键名
    public $pass_param_key_mean_timestamp_to = 'to';

    // 传递过来的参数 代表开始的时间戳的键名
    public $pass_param_key_mean_timestamp_from = 'from';

    public $time_table = null;

    public function create_time()
    {
        $to_key = $this->pass_param_key_mean_timestamp_to;

        $from_key = $this->pass_param_key_mean_timestamp_from;

        $time_column_name = $this->time_column_name;

        $request = $this->request;

        $from = (int)($request->{$from_key} ?? 0);

        $to = (int)($request->{$to_key} ?? time());

        $table = $this->time_table ?? $this->getTable();

        $this->builder->where(
            [
                [
                    $table . '.' . $time_column_name,
                    '>=',
                    $from,
                ],
                [
                    $table . '.' . $time_column_name,
                    '<=',
                    $to,
                ],
            ]
        );
    }

    /**
     * @param null $time_table
     */
    public function setTimeTable(string $time_table)
    {
        $this->time_table = $time_table;
    }
}