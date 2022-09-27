<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateBookingRequest;
use Illuminate\Http\Request;
use App\Http\Resources\BookingResource;
use App\Services\BookingDataService;
use App\Services\StoreBookingService;

/**
 * Class BookingAPIController
 * @package App\Http\Controllers\API
 */
class BookingAPIController extends AppBaseController
{



    /**
     * Display a listing of the Booking.
     * GET|HEAD /bookings
     *
     * @param Request request
     * @return Response
     */
    public function index(Request $request, BookingDataService $bookingDataService)
    {
        return $this->sendResponse(($bookingDataService->getAll($request->all())), __('messages.retrieved', ['model' => __('models/Booking.plural')]));
    }
    /**
     * Store a newly created Booking.
     * POST /bookings
     *
     * @param CreateBookingAPIRequest request
     *
     * @return Response
     */
    public function store(CreateBookingRequest $request, StoreBookingService $storeBookingService)
    {
        return $this->sendResponse(($storeBookingService->store($request->validated())), __('messages.saved', ['model' => __('models/Booking.singular')]));
    }
}
