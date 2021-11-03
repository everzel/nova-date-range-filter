<?php

namespace Everzel\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class DateRangeFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'nova-date-range-filter';

    /**
     * Create a new filter instance.
     *
     * @param  string  $column
     * @return void
     */
    public function __construct($column, $name = null)
    {
        $this->column = $column;
        $this->name = $name ?? $column;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $from = Carbon::parse($value[0])->startOfDay();
        if (count($value) == 1) {
            $to = Carbon::parse($value[0])->endOfDay();
        } else {
            $to = Carbon::parse($value[1])->endOfDay();
        }

        return $query->whereBetween($this->column, [$from, $to]);
    }

    public function enableTime()
    {
        $this->withMeta(['enableTime' => true]);
        return $this;
    }

    public function dateFormat($format)
    {
        $this->withMeta(['dateFormat' => $format]);
        return $this;
    }

    public function placeholder($placeholder)
    {
        $this->withMeta(['placeholder' => $placeholder]);
        return $this;
    }

    public function options(Request $request)
    {
        return [
            'firstDayOfWeek' => 1,
            'separator' => '-',
            'enableTime' => false,
            'enableSeconds' => false,
            'twelveHourTime' => false,
            'mode' => 'range'
        ];
    }

    /**
     * Get the key for the filter.
     *
     * @return string
     */
    public function key()
    {
        return 'timestamp_' . $this->column;
    }

    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return __('Filter By ') . $this->name;
    }
}
