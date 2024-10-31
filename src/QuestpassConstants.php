<?php

namespace Questpass;

class QuestpassConstants {

	/**
	 * Base domains.
	 */
	const AUTHENTICATION_BASE = 'https://system.questpass.pl';
	const API_BASE            = 'https://api.questpass.pl';

	/**
	 * URLs for users.
	 */
	const USER_SUBSCRIBER_URL = self::AUTHENTICATION_BASE . '/subscriber';
	const USER_PUBLISHER_URL  = self::AUTHENTICATION_BASE . '/publisher';

	/**
	 * Questpass API.
	 */
	const API_SERVICES_URL            = self::API_BASE . '/v1/services';
	const API_SERVICES_STATUS_URL     = self::API_BASE . '/v1/services/%s/status';
	const API_SERVICES_JAVASCRIPT_URL = self::API_BASE . '/v1/services/%s/javascript';
	const API_OAUTH_TOKEN_URL         = self::API_BASE . '/oauth2/token';
	const API_OAUTH_DETAILS_URL       = self::API_BASE . '/oauth2/me';

	/**
	 * WordPress REST API.
	 */
	const REST_API_BASE                        = 'questpass/v1';
	const REST_API_ROUTE_OPTIONS               = 'options';
	const REST_API_ROUTE_DEBUG                 = 'debug';
	const REST_API_ROUTE_UPDATE_JAVASCRIPT     = 'update/javascript';
	const REST_API_ROUTE_UPDATE_SERVICE_STATUS = 'update/service-status';
	const REST_API_ROUTE_USER_LOGIN_CALLBACK   = 'subscription-user/callback';
	const REST_API_ROUTE_USER_LOGIN_REDIRECT   = 'subscription-user/login';
	const REST_API_ROUTE_USER_LOGOUT           = 'subscription-user/logout';
	const REST_API_ROUTE_USER_UPDATE           = 'subscription-user/update';

	/**
	 * Quest config.
	 */
	const CONFIG_JAVASCRIPT_EXPIRATION_TIME = 86400;
}
