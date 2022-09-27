<?php

namespace App\Repositories;

use App\Models\Setting;

/**
 * Class SettingRepository
 * @package App\Repositories
 */
class SettingRepository extends BaseRepository
{

    /**
     * Constructor
     *
     * @param Setting $model
     */
    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    /**
     * Return single searchable 
     *
     * @return array
     */
    private $fieldSearchable = ['event_id' => '=', 'key' => '=', 'value' => '='];

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
