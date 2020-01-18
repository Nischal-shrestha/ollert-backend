<?php

namespace App\Traits;



/**
 * Trait ResponseHelper
 * 
 * Helps in composing response for api requests
 * 
 * @package App\Traits
 */

trait ResponseHelper
{


    /**
     * Takes in a variable by reference and
     * sets its index and value
     * 
     * @param array &$response
     * @param string $error
     * @param string $message
     */
    protected function composeError(&$response, $error, $message)
    {
        $response["error"] = $error;
        $response["message"] = $message;
    }

    /**
     * Takes in a variable by reference and
     * sets its index and value
     * 
     * @param array &$response
     * @param string $status
     * @param string $message
     */
    protected function composeStatus(&$response, $status, $message)
    {
        $response["status"] = $status;
        $response["message"] = $message;
    }
}
