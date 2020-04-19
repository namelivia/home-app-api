<?php

namespace App\Lib\Constants;

/**
 * This is a convenience class which represents
 * the different HTTP status codes, so that
 * they can be accessed through constants.
 *
 */
class HttpStatusCodes {
	
	// 2xx
	const OK							= 200;
	const CREATED						= 201;
	const ACCEPTED						= 202;
	const NON_AUTHORITATIVE_INFORMATION	= 203;
	const NO_CONTENT					= 204;
	const RESET_CONTENT					= 205;
	const PARTIAL_CONTENT				= 206;

	// 4xx
	const BAD_REQUEST					= 400;
	const UNAUTHORIZED					= 401;
	const FORBIDDEN						= 403;
	const NOT_FOUND						= 404;
	const METHOD_NOT_ALLOWED			= 405;
	const NOT_ACCEPTABLE				= 406;
	const CONFLICT						= 409;
	const GONE							= 410;
	const IM_A_TEAPOT					= 418;
	const UNPROCESSABLE_ENTITY			= 422;
	const TOO_MANY_REQUESTS				= 429;

	// 5xx
	const INTERNAL_SERVER_ERROR			= 500;
	const NOT_IMPLEMENTED				= 501;
	const BAD_GATEWAY					= 502;
	const SERVICE_UNAVAILABLE			= 503;
	const GATEWAY_TIMEOUT				= 504;
}
