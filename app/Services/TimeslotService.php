<?php

namespace App\Services;

use App\Models\Timeslot;
use App\Repositories\TimeslotRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TimeslotService
 * @package App\Services
 */
class TimeslotService
{

    /**
     * Constructor
     *
     * @param TimeslotRepository $repository
     */
    public function __construct(protected TimeslotRepository $repository)
    {
    }

    public function checkTimeslotRange($timeslot, string $startTime, string $endTime): bool
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        return $timeslot && $endTime > $startTime && strtotime($timeslot->opening_time) <= $startTime && strtotime($timeslot->closing_time) >= $endTime;
    }

    public function getEventTimeSlotByDay(int $eventId, int $day): mixed
    {
        return $this->repository->allQuery(['day' => $day, 'event_id' => $eventId])->first();
    }

    public function checkValidTimeSlot(int $eventId, string $date, string $startTime, string $endTime, array $interval = []): bool
    {
        $timeslot = $this->getEventTimeSlotByDay($eventId, \getDayOfDate($date));

        return  $this->checkTimeslotRange($timeslot, $startTime, $endTime) && $this->checkValidSlot($timeslot, $startTime, $endTime, $interval);
    }

    private function checkValidSlot($timeslot, string $startTime, string $endTime, array $intervals = [])
    {
        if ($timeslot) {
            $openingTime = $timeslot->opening_time;
            $closingTime = strtotime($timeslot->closing_time);
            $slotTime = $timeslot->slot_time;

            $bufferTime = $timeslot->buffer_time;

            $newStartTime = strtotime($openingTime);
            $startTime = \strtotime($startTime);

            $endTime = \strtotime($endTime);
            $valid = false;

            while ($newStartTime < $closingTime) {
                $newEndTime = \strtotime("+$slotTime minutes", $newStartTime);
                $newIntervalStartTime = $newEndTime;
                $hasInterval = false;

                foreach ($intervals as $interval) {
                    $intervalStartTime = \strtotime($interval['start_time']);
                    $intervalEndTime = \strtotime($interval['end_time']);

                    if (!(($newStartTime < $intervalStartTime || $newStartTime > $intervalStartTime) && ($newEndTime < $intervalStartTime || $newEndTime > $intervalEndTime))) {
                        $hasInterval = true;
                        $newIntervalStartTime = $intervalEndTime;
                    }
                }
                if ($hasInterval) {
                    $newStartTime = $newIntervalStartTime;
                } else {
                    if ($startTime == $newStartTime && $endTime == $newEndTime)
                        $valid = true;
                    $newStartTime = \strtotime("+$bufferTime minutes", $newEndTime);
                }
            }

            return $valid;
        }
        return false;
    }


    public function getValidSlot($timeslot, array $intervals = []): array
    {
        $slots = [];
        if ($timeslot) {
            $openingTime = $timeslot->opening_time;
            $closingTime = strtotime($timeslot->closing_time);
            $slotTime = $timeslot->slot_time;

            $bufferTime = $timeslot->buffer_time;

            $newStartTime = strtotime($openingTime);

            while ($newStartTime < $closingTime) {
                $newEndTime = \strtotime("+$slotTime minutes", $newStartTime);
                $newIntervalStartTime = $newEndTime;
                $hasInterval = false;

                foreach ($intervals as $interval) {
                    $intervalStartTime = \strtotime($interval['start_time']);
                    $intervalEndTime = \strtotime($interval['end_time']);

                    if (!(($newStartTime < $intervalStartTime || $newStartTime > $intervalStartTime) && ($newEndTime < $intervalStartTime || $newEndTime > $intervalEndTime))) {
                        $hasInterval = true;
                        $newIntervalStartTime = $intervalEndTime;
                    }
                }
                if ($hasInterval) {
                    $newStartTime = $newIntervalStartTime;
                } else {
                    if ($newEndTime < $closingTime)
                        $slots[] = ['start_time' => date('H:i', $newStartTime), 'end_time' => date('H:i', $newEndTime)];
                    $newStartTime = \strtotime("+$bufferTime minutes", $newEndTime);
                }
            }
        }
        return $slots;
    }

    private function checkInterval(array $intervals, string $startTime, string $endTime)
    {
        foreach ($intervals as $interval) {
            $intervalStartTime = \strtotime($interval['start_time']);
            $intervalEndTime = \strtotime($interval['end_time']);
            if (!(($startTime < $intervalStartTime || $startTime > $intervalStartTime) && ($endTime < $intervalStartTime || $endTime > $intervalEndTime))) {
                return true;
            }
        }
        return false;
    }

    public function getAll(): Collection
    {
        return $this->repository->all();
    }
}
