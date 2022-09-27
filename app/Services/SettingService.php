<?php

namespace App\Services;

use App\Models\Setting;
use App\Repositories\SettingRepository;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SettingService
 * @package App\Services
 */
class SettingService
{

    /**
     * Constructor
     *
     * @param SettingRepository $repository
     */
    public function __construct(protected SettingRepository $repository)
    {
    }

    public function checkEventDaysRange(int $eventId, string $date): bool
    {

        $setting = $this->repository->allQuery(['event_id' => $eventId, 'key' => 'max_days_available'])->first();
        return $setting && $setting->value >= $this->dateDifference($date);
    }

    public function getAllMaxDaysAvailable(): Collection
    {
        return $this->repository->all(['key' => 'max_days_available']);
    }

    private function dateDifference(string $date)
    {
        $today = new DateTime();
        $date = new DateTime($date);
        return $date->diff($today)->format('%a');
    }

    public function getEventSlot(int $event_id): int
    {
        $setting = $this->repository->allQuery(['event_id' => $event_id, 'key' => 'max_client_per_slot'])->first();
        if ($setting)
            return $setting->value;
        return 0;
    }

    public function getAllEventSlot()
    {
        return $this->repository->all(['key' => 'max_client_per_slot']);
    }
}
