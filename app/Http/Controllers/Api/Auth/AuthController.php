<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;

use App\Traits\ResponseHelper;

use App\Models\User\User;
use Psr\Http\Message\ResponseInterface;

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
     * @return ResponseInterface $response
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
     * @param Request $request
     * @return JsonResponse
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
            $this->composeError($response, "invalid_credentails", "The user credentails were incorrect.");
        } else {
            //issue a password grant type token
            $tokenData = $this->issuePasswordToken($credentials);
            if ($tokenData->getStatusCode() == 200) {
                $response = json_decode((string)$tokenData->getBody(), true);
                $response["token_grant"] = "PASSWORD";
            } else {
                $this->composeError($response, "failed", "There is something wrong server side.");
            }
        }

        return response()->json($response, 200);
    }

    /**
     * This method registers user if they provide unique Email
     * and a valid password in their request. If they are
     * validated it returns a token and a refresh token
     *
     * @param SignUpRequest $request
     * @return JsonResponse
     */

    public function register(SignUpRequest $request)
    {
        //name, email, password, password_confirmation
        $validated = $request->validated();
        $response = null;

        $userDetails = $request->only('name', 'email', 'password', 'password_confirmation');
        $newUser = new User;
        $newUser->name = $request->query('name');
        $newUser->email = $request->query('email');
        $newUser->password = $request->query('password');

        if ($newUser->save()) {
            $tokenData = $this->issuePasswordToken($credentials);
            if ($tokenData->getStatusCode() == 200) {
                $response = json_decode((string)$tokenData->getBody(), true);
                $response["token_grant"] = "PASSWORD";
                $this->composeStatus($response, 'created', 'The user has been created');
            } else {
                $this->composeError($response,"failed","The Server is having some issues.");
            }
            return response()->json($response, 201);
        } else {
            $this->composeStatus($response, 'failed', 'Failed to create user!');
            return response()->json($response);
        }

    }

    /**
     * This method accepts refresh_token in the request
     * and issues a new token for the user
     *
     * @param Request $request
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

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * This method loops through the authenticated user's tokens
     * and revokes them individually
     * @param Request $request
     * @return JsonResponse
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

        return response()->json($response, 200);
    }
}
