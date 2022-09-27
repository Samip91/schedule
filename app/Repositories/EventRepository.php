<?php

namespace App\Repositories;

use App\Models\Event;

/**
 * Class EventRepository
 * @package App\Repositories
 */
class EventRepository extends BaseRepository
{

    /**
     * Constructor
     *
     * @param Event $model
     */
    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    /**
     * Return single searchable 
     *
     * @return array
     */
    private $fieldSearchable = [];

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
