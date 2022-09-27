<?php

namespace App\Repositories;

use App\Models\Leave;

/**
 * Class LeaveRepository
 * @package App\Repositories
 */
class LeaveRepository extends BaseRepository
{

    /**
     * Constructor
     *
     * @param Leave $model
     */
    public function __construct(Leave $model)
    {
        $this->model = $model;
    }

    /**
     * Return single searchable 
     *
     * @return array
     */
    private $fieldSearchable = [
        'event_id' => '=',
        'date' => '='
    ];

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
