<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use Illuminate\Database\Eloquent\Collection;


/**
 * Class BookingDataService
 * @package App\Services
 */
class BookingDataService
{

    /**
     * Constructor
     *
     * 
     */
    public function __construct(private TimeslotService $timeslotService, private DateAvailabilityService $dateAvailabilityService, private SettingService $settingService, private IntervalService $intervalService, private BookingRepository $bookingRepository, private EventService $eventService)
    {
    }

    public function getAll()
    {

        $dates = $this->getAllAvailableDates();
        $intervals = $this->intervalService->getAll();
        $uniqueDates = $this->getUniqueDates($dates);
        $bookings = $this->getBookings($uniqueDates);
        return $this->getValidTimeSlot($intervals->toArray(), $dates, $bookings);
    }

    public function getAllAvailableDates()
    {

        $dates = [];
        $leaves = $this->getLeaveDays();
        foreach ($this->getMaxDays() as $key => $value) {
            $eventDates = $this->getDates($value);

            if (isset($leaves[$key])) {
                $eventDates = \array_diff($eventDates, $leaves[$key]);
            }
            $dates[$key] = $eventDates;
        }
        return $dates;
    }

    public function getMaxDays(): array
    {
        $days = [];
        $maxDays = $this->settingService->getAllMaxDaysAvailable();

        foreach ($maxDays as $maxDay) {
            $days[$maxDay->event_id] = $maxDay->value;
        }

        return $days;
    }

    public function getLeaveDays(): array
    {
        $leaveDays = [];
        $leaves = $this->dateAvailabilityService->getLeaveDays();

        foreach ($leaves as $leave) {
            $leaveDays[$leave->event_id][] = $leave->date;
        }

        return $leaveDays;
    }

    public function getDates(int $noOfDays)
    {
        $dates = [];

        if ($noOfDays > 0) {
            $dates[] = \date('Y-m-d');
            for ($i = 1; $i < $noOfDays; $i++) {
                $dates[] = \date('Y-m-d', \strtotime("+$i days"));
            }
        }

        return $dates;
    }


    public function getUniqueDates(array $dates): array
    {
        $uniqueDates = [];
        foreach ($dates as $item) {

            $uniqueDates = array_merge($uniqueDates, $item);
        }
        return array_unique($uniqueDates);
    }
    public function getBookings(array $dates): Collection
    {
        return $this->bookingRepository->allQuery()->whereIn('booking_date', $dates)->get();
    }

    private function getValidTimeSlot(array $intervals, array $dates, Collection $bookings)
    {
        $timeslots = $this->timeslotService->getAll();

        $eventSlots = $this->settingService->getAllEventSlot();
        $maxClientPerSlots = [];
        $eventIds = [];
        foreach ($eventSlots as $eventSlot) {
            $maxClientPerSlots[$eventSlot->event_id] = $eventSlot->value;
            $eventIds[] = $eventSlot->event_id;
        }
        $events = $this->eventService->getEvents($eventIds);
        $slots = [];

        foreach ($dates as $eventId => $dateArr) {
            $maxClientPerSlot = $maxClientPerSlots[$eventId];
            $eventName = $events->where('id', $eventId)->first();
            foreach ($dateArr as $date) {
                $day = getDayOfDate($date);
                if ($timeslots->contains('day', $day)) {
                    $timeslot = $timeslots->where('day', $day)->where('event_id', $eventId)->first();


                    $timeSlotData
                        = $this->timeslotService->getValidSlot($timeslot, $intervals);

                    foreach ($timeSlotData as $data) {
                        $booking = $bookings->where('event_id', $eventId)->where('booking_date', $date)->where('start_time', date('H:i:s', strtotime($data['start_time'])))->count();
                        $booking = $booking > 0 ? $booking : 0;
                        $remainingSlot =  $maxClientPerSlot - $booking;
                        if ($remainingSlot > 0) {

                            if (date('Y-m-d', strtotime('today')) == date('Y-m-d', strtotime($date)) && date('Y-m-d H:i:s', strtotime('now')) > date('Y-m-d H:i:s', strtotime($data['start_time']))) {
                                continue;
                            }
                            $slots[$eventName->name][$date][] = \array_merge($data, ['remaining_slots' => $remainingSlot]);
                        }
                    }
                }
            }
        }

        return $slots;
    }
}
