<?php

namespace App\Http\Controllers;

use App\User; 
use Validator;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth; 
use Laravel\Passport\Client as OClient;

class ApiAuthController extends Controller
{
    public function __construct(Client $client) {
        $this->http = $client;
    }

    public function register (RegisterRequest $request, User $model)
    {
        $email = $request->email;
        $password = $request->password;

        $model->create($request->merge([
            'password'      => bcrypt($password)
        ])->all());

        $response = $this->getTokenAndRefreshToken($email, $password);
        return response()->json($response["data"], 201);
    }

    public function login(LoginRequest $request) {
        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $response = $this->getTokenAndRefreshToken($email, $password);
            $data = $response["data"];
            $statusCode =  $response["statusCode"];
        } 
        else 
        {
            $data = ['error'=>'Unauthorised'];
            $statusCode =  401;
        }

        return response()->json($data, $statusCode);
    }

    public function refreshToken(Request $request) 
    {
        if (is_null($request->Refreshtoken)) 
        {
            return response()->json(['error'=>'Unauthorised'], 401);
        }

        $refresh_token = $request->Refreshtoken;
        $Oclient = $this->getOClient();
        $formParams = [ 'grant_type' => 'refresh_token',
                        'refresh_token' => $refresh_token,
                        'client_id' => $Oclient->id,
                        'client_secret' => $Oclient->secret,
                        'scope' => '*'];

        $response = $this->sendRequest("/oauth/token", $formParams);
        return response()->json($response["data"], $response["statusCode"]);
    }

    public function getTokenAndRefreshToken(string $email, string $password) 
    {
        $Oclient = $this->getOClient();
        $formParams = [ 'grant_type' => 'password',
                        'client_id' => $Oclient->id,
                        'client_secret' => $Oclient->secret,
                        'username' => $email,
                        'password' => $password,
                        'scope' => '*'
                    ];

        return $this->sendRequest("/oauth/token", $formParams);
    }

    public function sendRequest(string $route, array $formParams) 
    {
        try 
        {
            $url = ENV('MAIN_URL').$route;
            $response = $this->http->request('POST', $url, ['form_params' => $formParams]);

            $statusCode = 200;
            $data = json_decode((string) $response->getBody(), true);
        } 
        catch (ClientException $e) 
        {
            echo $e->getMessage();
            $statusCode = $e->getCode();
            $data = ['error'=>'OAuth client error'];
        }

        return ["data" => $data, "statusCode"=>$statusCode];
    }

    public function getOClient() {
        return OClient::where('password_client', 1)->first();
    }
}
