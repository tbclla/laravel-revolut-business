<?php

namespace tbclla\Revolut\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use tbclla\Revolut\Auth\AuthorizationCode;
use tbclla\Revolut\Auth\Requests\AuthorizationCodeRequest;
use tbclla\Revolut\Auth\TokenManager;

class AuthorizationController extends Controller
{
    /**
     * @param \illuminate\Http\Request $request
     * @param \tbclla\Revolut\Auth\Requests\AuthorizationCodeRequest $authRequest
     * @return \illuminate\Http\RedirectResponse
     */
    public function create(Request $request, AuthorizationCodeRequest $authRequest)
    {
        // store the state and an optional redirect url
        session()->put($authRequest->state, [
            'redirect_uri' => $request->after_success
        ]);

        // redirect to Revolut's OAuth flow
        return redirect($authRequest->build());
    }

    /**
     * @param \illuminate\Http\Request $request
     * @param \tbclla\Revolut\Auth\TokenManager $tokenManager
     * @return mixed
     */
    public function store(Request $request, TokenManager $tokenManager)
    {
        // verify that the request contains the required parameters
        if (!$request->state or !$request->code) {
            abort(405, 'Invalid Request');
        }

        // verify that the session holds a matching state
        if (!session()->has($request->state)) {
            abort(405, 'Invalid State');
        }

        $authCode = new AuthorizationCode($request->code);
        
        $tokenManager->requestAccessToken($authCode);

        $state = session()->pull($request->state);
        
        $redirect = $state['redirect_uri'];
        
        return $redirect
            ? redirect($redirect)
            : response('Authorization successful', 200);
    }
}
