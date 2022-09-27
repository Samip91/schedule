<?php

namespace App\Repositories;

use App\Models\Booking;

/**
 * Class BookingRepository
 * @package App\Repositories
 */
class BookingRepository extends BaseRepository
{

    /**
     * Constructor
     *
     * @param Booking $model
     */
    public function __construct(Booking $model)
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
        'booking_date' => '=',
        'start_time' => '=',
        'end_time' => '=',
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
