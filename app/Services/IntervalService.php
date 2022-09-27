<?php

namespace App\Services;

use App\Repositories\IntervalRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class IntervalService
 * @package App\Services
 */
class IntervalService
{

    /**
     * Constructor
     *
     * @param IntervalRepository $repository
     */
    public function __construct(protected IntervalRepository $repository)
    {
    }

    public function checkInterval(int $eventId, string $startTime, string $endTime): bool
    {
        $intervals = $this->getInterval($eventId);
        if (!$intervals && $startTime >= $endTime)
            return false;

        $startTime = \strtotime($startTime);
        $endTime = \strtotime($endTime);

        foreach ($intervals as $interval) {
            $intervalStartTime = strtotime($interval->start_time);
            $intervalEndTime = \strtotime($interval->end_time);
            if (!(($startTime < $intervalStartTime || $startTime > $intervalStartTime) && ($endTime < $intervalStartTime || $endTime > $intervalEndTime))) {
                return false;
            }
        }


        return true;
    }

    public function getInterval(int $eventId): Collection
    {
        return $this->repository->all(['event_id' => $eventId]);
    }

    public function getAll(): Collection
    {
        return $this->repository->all();
    }
}
