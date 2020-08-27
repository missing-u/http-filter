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

    private $time_column_name = 'created_at';

    // 传递过来的参数 代表结束的时间戳的键名
    private $pass_param_key_mean_timestamp_to = 'to';

    // 传递过来的参数 代表开始的时间戳的键名
    private $pass_param_key_mean_timestamp_from = 'from';

    private $time_table = null;

    public function create_time()
    {
        $to_key = $this->pass_param_key_mean_timestamp_to;

        $from_key = $this->pass_param_key_mean_timestamp_from;

        $time_column_name = $this->time_column_name;

        $request = $this->request;

        $from = $request[ $from_key ] ?? 0;

        $to = $request[ $to_key ] ?? time();

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

    public function setTimeColumnName(string $time_column_name)
    {
        $this->time_column_name = $time_column_name;
    }

    public function setPassParamKeyMeanTimestampTo(string $pass_param_key_mean_timestamp_to)
    {
        $this->pass_param_key_mean_timestamp_to = $pass_param_key_mean_timestamp_to;
    }

    public function setPassParamKeyMeanTimestampFrom(string $pass_param_key_mean_timestamp_from)
    {
        $this->pass_param_key_mean_timestamp_from = $pass_param_key_mean_timestamp_from;
    }

    /**
     * @param null $time_table
     */
    public function setTimeTable($time_table)
    {
        $this->time_table = $time_table;
    }
}