<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;

use App\Traits\ResponseHelper;

use App\Models\User\User;

class AuthController extends Controller
{
    use ResponseHelper;

    private $http; //for self requesting tokens

    public function __construct()
    {
        $this->http = new Client(['http_errors' => false]);

        $this->middleware('auth:api')->only(['logout', 'validateToken']);
    }


    /**
     * This function issues a grant_type password token
     * using user's credentials (Email, password)
     * 
     * @param array $credentials
     * @return var $response
     */
    private function issuePasswordToken($credentials)
    {
        $response = $this->http->post(env("APP_URL") . "/oauth/token", [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env("CLIENT_ID"),
                'client_secret' => env("CLIENT_SECRET"),
                'username' => $credentials['email'],
                'password' => $credentials['password'],
                'scope' => '',
            ],
        ]);
        return $response;
    }

    /**
     * This method logs in user if they provide Email
     * and password in their request. If they are 
     * validated it returns a token and a refresh token
     * 
     * @param Illuminate\Http\Request $request
     * @return json
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = null;
        $response = null;

        //Attempt to login and get user object
        if (Auth::attempt(['Email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();
            Auth::logout();
        }

        if (is_null($user)) {
            $this->composerError($response, "invalid_credentails", "The user credentails were incorrect.");
        } else {
            //issue a password grant type token
            $response = json_decode((string) $this->issuePasswordToken($credentials)->getBody(), true);
            $response["token_grant"] = "PASSWORD";
        }

        return $response;
    }

    /**
     * This method accepts refresh_token in the request
     * and issues a new token for the user
     * 
     * @param Illuminate\Http\Request $request
     * @return json
     */
    public function refresh(Request $request)
    {
        $request = $request->only('refresh_token');
        $response = $this->http->post(env("APP_URL") . "/oauth/token", [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => env("CLIENT_ID"),
                'client_secret' => env("CLIENT_SECRET"),
                'refresh_token' => $request["refresh_token"],
                'scope' => '',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * This method loops through the authenticated user's tokens
     * and revokes them individually
     * @param Illuminate\Http\Request $request
     * @return json
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $response = null;
        if (!is_null($user)) {
            if ($user->token()->revoke()) {
                $this->composeStatus($response, "logout_success", "logout successfull");
            } else {
                $this->composeStatus($response, "logout_failed", "logout failed");
            }
        } else {
            $this->composeError($response, "invalid_request", "Invalid Request");
        }

        return $response;
    }
}
