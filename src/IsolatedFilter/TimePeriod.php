<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 10/16/18
 * Time: 3:30 PM
 */

namespace HttpFilter\IsolatedFilter;


use Carbon\Carbon;

trait TimePeriod
{
    static $invoke_regardless_request_property_create_time = "create_time";

    protected $time_column_name = "created_at";

    /**
     * @param string $time_column_name
     */
    public function setTimeColumnName(string $time_column_name) : void
    {
        $this->time_column_name = $time_column_name;
    }

    public function create_time()
    {
        $point = $this->getPoint();

        [
            'begin' => $start,
            'end'   => $end,
        ] = $point;

        return $this->builder
            ->where(
                [
                    [
                        $this->getTable() . '.' . $this->time_column_name,
                        '>',
                        Carbon::createFromTimestamp($start)->toDateTimeString(),
                    ],
                    [
                        $this->getTable() . '.' . $this->time_column_name,
                        '<',
                        Carbon::createFromTimestamp($end)->toDateTimeString(),
                    ],
                ]
            );

    }

    //这里有问题　绑定死了
    //但是无所谓　这里不影响主逻辑
    public function getPoint()
    {
        //beigin_time   兼容　start
        //end_time      兼容　end
        $start_time = $this->request->input("start_time", 0);
        $end_time   = $this->request->input("end_time", time());

        $start = $this->request->input("start", 0);
        $end   = $this->request->input("end", time());

        if ( !empty($start_time)) {
            $begin = $start_time;
        } elseif ( !empty($start)) {
            $begin = $start;
        } else {
            $begin = 0;
        }

        if ( !empty($end_time)) {
            $last = $end_time;
        } elseif ( !empty($end)) {
            $last = $end;
        } else {
            $last = time();
        }


        return [
            'begin' => $begin,
            'end'   => $last,
        ];
    }
}