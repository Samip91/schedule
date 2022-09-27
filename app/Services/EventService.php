<?php

namespace App\Services;

use App\Repositories\EventRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EventService
 * @package App\Services
 */
class EventService
{

    /**
     * Constructor
     *
     * @param EventRepository $repository
     */
    public function __construct(protected EventRepository $repository)
    {
    }
    public function getEvents(array $ids): Collection
    {
        return $this->repository->allQuery()->whereIn('id', $ids)->get();
    }
}
