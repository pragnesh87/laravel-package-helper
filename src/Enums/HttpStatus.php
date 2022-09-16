<?php

namespace Pragnesh\LaravelPackageHelper\Enums;

enum HttpStatus: int
{
	case OK = 200;
	case Created = 201;
	case Accepted = 202;
	case Non_Authoritative = 203;
	case No_Content = 204;
	case Moved_Permanently = 301;
	case Found = 302;
	case Bad_Request = 400;
	case Unauthorized = 401;
	case Forbidden = 403;
	case Not_Found = 404;
	case Method_Not_Allowed = 405;
	case Not_Acceptable = 406;
	case Internal_Server_Error = 500;
	case Not_Implemented = 501;
	case Bad_Gateway = 502;
	case Service_Unavailable = 503;
	case Gateway_Timeout = 504;
}