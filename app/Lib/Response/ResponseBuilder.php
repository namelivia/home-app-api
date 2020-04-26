<?php

namespace App\Lib\Response;

use App\Lib\Constants\HttpStatusCodes;
use Illuminate\Support\Facades\Response;

/**
 * This class allows returning error
 * responses with a unified data strcuture
 * by registering error details with a unique
 * error identifier.
 */
class ResponseBuilder
{
    private $registeredErrors;

    public function __construct()
    {
        $this->registeredErrors = [];
    }

    /**
     * Returns all currently registered errors.
     *
     * @return	array
     */
    public function getRegisteredErrors()
    {
        return $this->registeredErrors;
    }

    /**
     * Registers a single error for the given error
     * code.
     *
     * @param	int		$internalCode
     * @param	string	$description
     * @param	int		$httpCode
     */
    public function registerError($internalCode, $description, $httpCode)
    {
        $this->registeredErrors[$internalCode] = [
            'description' => $description,
            'http_code' => $httpCode,
        ];
    }

    /**
     * Convenience method for registering multiple
     * errors at once.
     *
     * This method expects an array of arrays,
     * each of the inner ones being either
     * an associative array with the following
     * keys:
     *	- internal_code
     *	- description
     *	- http_code
     *
     * Or an indexed array having 3 elements:
     * the code, the description and the HTTP status
     * (in that order).
     *
     * @param	array	$errors
     *
     * @throws	IllegalArgumentException
     */
    public function registerErrors(array $errors)
    {
        foreach ($errors as $error) {
            if (count($error) === 3) {
                $arr = array_diff(['internal_code', 'description', 'http_code'], array_keys($error));

                if (empty($arr)) {
                    $this->registerError($error['internal_code'], $error['description'], $error['http_code']);
                } else {
                    $this->registerError($error[0], $error[1], $error[2]);
                }
            } else {
                throw new \InvalidArgumentException('Error attributes mismatch');
            }
        }
    }

    /**
     * Returns a Laravel response for the given
     * error code.
     *
     * The contents of the response will be a
     * JSON representation of the registered error
     * matching the provided code.
     *
     * @param	int		$internalCode
     * @param	array	$validationErrors
     *
     * @throws	InvalidArgumentException
     *
     * @return	Illuminate\Http\JsonResponse
     */
    public function buildErrorResponse($internalCode, $validationErrors = null)
    {
        if (array_key_exists($internalCode, $this->registeredErrors)) {
            $error = $this->registeredErrors[$internalCode];

            $response = ['code' => $internalCode, 'description' => $error['description']];

            if ($validationErrors != null) {
                $response['validation_errors'] = $validationErrors;
            }

            return Response::json($response, $error['http_code']);
        } else {
            throw new \InvalidArgumentException("The specified internal error code '$internalCode' is not registered");
        }
    }

    /**
     * Alias for buildErrorResponse().
     *
     * @see	ResponseBuilder::buildErrorResponse()
     */
    public function error($internalCode, $validationErrors = null)
    {
        return $this->buildErrorResponse($internalCode, $validationErrors);
    }

    /**
     * Returns a Laravel JSON response with the
     * given content and, optionally, a custom HTTP
     * status code.
     *
     * If no code is given, it will default to
     * 200.
     *
     * @param	mixed	$data
     * @param	int		$httpCode
     *
     * @return	Illuminate\Http\JsonResponse
     */
    public function buildSuccessResponse($data, $httpCode = HttpStatusCodes::OK)
    {
        return Response::json($data, $httpCode);
    }

    /**
     * Alias for buildSuccessResponse().
     *
     * @see ResponseBuilder::buildSuccessResponse()
     */
    public function success($data, $httpCode = HttpStatusCodes::OK)
    {
        return $this->buildSuccessResponse($data, $httpCode);
    }
}
