<?php

namespace App\Repositories;

use App\Models\Interval;

/**
 * Class IntervalRepository
 * @package App\Repositories
 */
class IntervalRepository extends BaseRepository
{

    /**
     * Constructor
     *
     * @param Interval $model
     */
    public function __construct(Interval $model)
    {
        $this->model = $model;
    }

    /**
     * Return single searchable 
     *
     * @return array
     */
    private $fieldSearchable = ['event_id' => '='];

    /**
     * Return searchable
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }
}
