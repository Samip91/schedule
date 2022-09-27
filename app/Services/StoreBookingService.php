<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Event;
use App\Repositories\BookingRepository;
use App\Repositories\EventRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StoreBookingService
 * @package App\Services
 */
class StoreBookingService
{

    /**
     * Constructor
     *
     * @param BookingRepository $repository
     */
    public function __construct(private BookingRepository $repository, private DateAvailabilityService $dateAvailabilityService, private SettingService $settingService, private IntervalService $intervalService, private TimeslotService $timeslotService, private EventRepository $eventRepository)
    {
    }

    // /**
    //  * get all Booking
    //  *
    //  * @param array $search
    //  * @return LengthAwarePaginator
    //  */
    // public function getAll(array $search): LengthAwarePaginator
    // {
    //     return $this->repository->paginate();
    // }

    /**
     * store Booking
     *
     * @param array $input
     * @return bool
     */
    public function store(array $inputs): bool
    {
        $valid = true;
        if (sizeof($inputs) == 0) {
            throw ValidationException::withMessages(['bookings' => 'Invalid record ']);
        }
        foreach ($inputs as $input) {
            $date = $input['booking_date'];
            $eventId = $input['event_id'];
            $startTime = $input['start_time'];
            $endTime = $input['end_time'];

            if (!$this->validateEvent($eventId))
                throw new ModelNotFoundException('Event not found');

            if (!$this->validatePreviousTime($date, $startTime))
                throw ValidationException::withMessages(['time' => 'Time not available']);

            if ($this->dateAvailabilityService->checkEventHoliday($eventId, $date,)) {
                throw ValidationException::withMessages(['date' => 'Date not available']);
            }

            if (!$this->settingService->checkEventDaysRange($eventId, $date)) {
                throw ValidationException::withMessages(['date' => 'Date not available']);
            }

            if ($this->settingService->getEventSlot($eventId, $date) <= $this->getBookingCountByTimeSlot($eventId, $startTime, $endTime, $date)) {
                throw ValidationException::withMessages(['start_time' => 'Time not available']);
            }

            if (!$this->intervalService->checkInterval($eventId, $startTime, $endTime)) {
                throw ValidationException::withMessages(['start_time' => 'Time not available']);
            }

            if (!$this->timeslotService->checkValidTimeSlot($eventId, $date, $startTime, $endTime, $this->intervalService->getInterval($eventId)->toArray())) {
                throw ValidationException::withMessages(['start_time' => 'Time not available']);
            }
            $valid
                = $valid && $this->repository->create($input);
        }
        return $valid;
    }

    private function getBookingCountByTimeSlot(int $eventId, string $startTime, string $endTime, string $date): int
    {
        return $this->repository->allQuery(['event_id' => $eventId, 'start_time' => $startTime, 'end_time' => $endTime, 'booking_date' => $date])->count();
    }

    public function validatePreviousTime(string $date, string $startTime): bool
    {
        if (\strtotime($date) < strtotime('today')) {
            return false;
        }
        if (date('Y-m-d', strtotime('today')) == date('Y-m-d', strtotime($date)) && date('Y-m-d H:i:s', strtotime('now')) > date('Y-m-d H:i:s', strtotime($startTime))) {
            return false;
        }
        return true;
    }

    private function validateEvent(int $eventId): bool
    {
        $event = $this->eventRepository->getById($eventId);
        if (!$event) {
            return false;
        }
        return true;
    }
}
