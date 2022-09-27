<?php

namespace App\Repositories;

use App\Models\Timeslot;

/**
 * Class TimeslotRepository
 * @package App\Repositories
 */
class TimeslotRepository extends BaseRepository
{

    /**
     * Constructor
     *
     * @param Timeslot $model
     */
    public function __construct(Timeslot $model)
    {
        $this->model = $model;
    }

    /**
     * Return single searchable 
     *
     * @return array
     */
    private $fieldSearchable = ['day' => '=', 'event_id' => '='];

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
