<?php

namespace App\Services;

use App\Repositories\LeaveRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DateAvailabilityService
 * @package App\Services
 */
class DateAvailabilityService
{

    /**
     * Constructor
     *
     * @param LeaveRepository $repository
     */
    public function __construct(protected LeaveRepository $repository)
    {
    }

    /**
     *
     * @param string $date
     * @param integer $eventId
     * @return Collection
     */
    public function checkEventHoliday(int $eventId, string $date,): bool
    {
        if ($this->getEventLeaveByDate($eventId, $date)->count() > 0)
            return true;
        return \false;
    }

    public function getEventLeaveByDate(int $eventId, string $date): Collection
    {
        return $this->repository->all(search: ['date' => $date, 'event_id' => $eventId]);
    }

    public function getLeaveDays(): Collection
    {
        return $this->repository->all();
    }
}
