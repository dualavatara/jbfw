<?php

/**
 * Base exception class
 */
class FunzayAPIException extends Exception {}

/**#@+
 * Interfaces
 */
interface IHttpException {
	const NO_CONTENT					= 204;
	const NOT_MODIFIED					= 304;
	const BAD_REQUEST					= 400;
	const UNAUTHORIZED					= 401;
	const NOT_FOUND						= 404;
	const REQUEST_TIMEOUT				= 408;
	const INTERNAL_SERVER_ERROR_HTTP	= 500;
	const SERVICE_UNAVAILABLE			= 503;
	const ACCESS_DENIED_HTTP			= 666;
	const TOO_LARGE_DATA_HTTP			= 699;

	public function getHttpCode();
	
	public function getHttpText();
}

interface IRPCException {
	const PARSE_ERROR					= -32700;
	const INVALID_REQUEST				= -32600;
	const METHOD_NOT_FOUND				= -32601;
	const INVALID_PARAMS				= -32602;
	const INTERNAL_SERVER_ERROR_RPC		= -32603;
	const ACCESS_DENIED_RPC				= -32066;
	const TOO_LARGE_DATA_RPC			= -32099;
	const OBJECT_NOT_FOUND   			= -32100;
	const OPERATION_NOT_PERMITTED		= -32101;

	public function getRpcCode();
	
	public function getRpcText();
}
/**#@-*/

/**#@+
 * Base Exception classes
 */
abstract class WebException
	extends FunzayAPIException
	implements IHttpException
{
	public function getHttpCode() {
		return $this->getCode();
	}

	public function getHttpText() {
		return $this->getMessage();
	}

}

class RPCException
	extends FunzayAPIException
	implements IRPCException
{
	public function getRpcCode() {
		return $this->getCode();
	}

	public function getRpcText() {
		return $this->getMessage();
	}
}

class CommonException 
	extends FunzayAPIException
	implements IHttpException, IRPCException
{
	public function getHttpCode() {
		return $this->getCode();
	}

	public function getHttpText() {
		return $this->getMessage();
	}

	public function getRpcCode() {
		return $this->getCode();
	}

	public function getRpcText() {
		return $this->getMessage();
	}	
}

/**#@+
 * Common (RPC and HTTP) errors
 */
class NotFoundException	extends CommonException
{
	public function __construct() {
		parent::__construct('Not Found exception.', self::NOT_FOUND);
	}
}

class UnauthorizedException extends CommonException {
	public function __construct() {
		parent::__construct('Unauthorized', self::UNAUTHORIZED);
	}
}

class SecurityProtocolException extends CommonException {
	public function __construct() {
		parent::__construct('Security protocol exception', self::UNAUTHORIZED);
	}
}

/**#@-*/

/**#@+
 * Http errors
 */
class NoContentException extends WebException {
	public function __construct() {
		parent::__construct('No Content', self::NO_CONTENT);
	}
}

class NotModifiedExcpetion extends WebException {
	public function __construct() {
		parent::__construct('Not Modified', self::NOT_MODIFIED);
	}
}

class BadRequestException extends  WebException {
	public function __construct() {
		parent::__construct('Bad Request', self::BAD_REQUEST);
	}
}

class WebInternalException extends WebException {
	public function __construct() {
		parent::__construct('Internal Server Error', self::INTERNAL_SERVER_ERROR_HTTP);
	}
}

class UnavailableException extends WebException {
	public function __construct() {
		parent::__construct('Service Unavailable', self::SERVICE_UNAVAILABLE);
	}
}

class RequestTimeoutException extends WebException {
	public function __construct() {
		parent::__construct('Request Timeout', self::REQUEST_TIMEOUT);
	}
}
/**#@-*/

/**#@+
 * RPC errors
 */
class ParseErrorException extends RPCException {
	public function __construct() {
		parent::__construct('Parse error', self::PARSE_ERROR);
	}
}
	
class InvalidRequestException extends RPCException {
	public function __construct() {
		parent::__construct('Invalid Request', self::INVALID_REQUEST);
	}
}

class MethodNotFoundException extends RPCException {
	public function __construct() {
		parent::__construct('Method not found', self::METHOD_NOT_FOUND);
	}
}

class InvalidParamsException extends RPCException {
	public function __construct() {
		parent::__construct('Invalid params', self::INVALID_PARAMS);
	}
}

class RPCInternalException extends RPCException {
	public function __construct() {
		parent::__construct('Internal server error', self::INTERNAL_SERVER_ERROR_RPC);
	}
}
/**#@-*/

/**
 * Implementation-defined errors
 */
class FunzayLogicException extends FunzayAPIException {}

class InvalidParameters 
	extends FunzayLogicException
	implements IHttpException, IRPCException
{
	public function getHttpCode() {
		return self::BAD_REQUEST;
	}

	public function getHttpText() {
		return 'Bad Request';
	}

	public function getRpcCode() {
		return self::INVALID_PARAMS;
	}

	public function getRpcText() {
		return 'Invalid params';
	}
}

class AccessDenied extends FunzayLogicException implements IHttpException, IRPCException {

	public function getHttpCode() {
		return self::ACCESS_DENIED_HTTP;
	}

	public function getHttpText() {
		return 'Access denied';
	}

	public function getRpcCode() {
		return self::ACCESS_DENIED_RPC;
	}

	public function getRpcText() {
		return 'Access denied';
	}
}

class TooLargeData extends FunzayLogicException implements IHttpException, IRPCException {

	public function getHttpCode() {
		return self::TOO_LARGE_DATA_HTTP;
	}

	public function getHttpText() {
		return 'Too large data';
	}

	public function getRpcCode() {
		return self::TOO_LARGE_DATA_RPC;
	}

	public function getRpcText() {
		return 'Too large data';
	}
}

class ObjectNotFound extends RPCException {
	public function __construct() {
		parent::__construct('Object not found', self::OBJECT_NOT_FOUND);
	}
}

class OperationNotPermitted extends RPCException {
	public function __construct() {
		parent::__construct('Operation not permitted', self::OPERATION_NOT_PERMITTED);
	}
}