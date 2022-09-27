<?php

namespace App\Http\Controllers;

use App\Utils\ResponseUtil;
use Response;

class AppBaseController extends Controller
{
    /**
     * 
     * @param mixed $result
     * @param string $message
     * @return Response
     */
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    /**
     * 
     * @param mixed $result
     * @param string $message
     * @return Response
     */
    public function sendPaginateResponse($result, $message)
    {
        return Response::json(ResponseUtil::makePaginateResponse($message, $result));
    }

    /**
     * 
     * @param mixed $result
     * @param string $message
     * @return Response
     */
    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

    /**
     * 
     * @param mixed $result
     * @param string $message
     * @return Response
     */
    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}
