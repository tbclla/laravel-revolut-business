<?php

namespace tbclla\Revolut\Controllers;

use Illuminate\Routing\Controller;
use tbclla\Revolut\Auth\AuthorizationCode;
use tbclla\Revolut\Auth\TokenManager;

class AuthorizationController extends Controller
{
	/**
	 * @param \tbclla\Revolut\Auth\TokenManager
	 * @return mixed
	 */
	public function __invoke(TokenManager $tokenManager)
	{
		// if the request does not contains a code and a state, abort
		if (!request('state') or !request('code')) {
			abort(405, 'Invalid Request');
		}

		// if the state doesn't match the latest state, abort
		if($tokenManager->getState()->value != request('state')) {
			abort(405, 'Invalid State');
		}

		$authCode = new AuthorizationCode(request('code'));
		$tokenManager->requestAccessToken($authCode);

		return response('Authorization successful', 200);
	}
}
