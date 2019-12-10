<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
// use Guzzle\Http\Client;


use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;


class AuthController extends Controller
{
    public function login(Request $request)
    {
    //    $http = new \GuzzleHttp\Client;

    //    try{
    //         $response = $http->post(config('services.passport.login_endpoint'), [
    //             'form_params' => [
    //                 'grant_type' => 'password',
    //                 'client_id' => config('services.passport.client_id'),
    //                 'client_secret' => config('services.passport.client_secret'),
    //                 'username' => $request->username,
    //                 'password' => $request->password,]
    //             ]);
            $request->request->add([
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                'username' => $request->username,
                'password' => $request->password,
            ]);
            $tokenRequest = Request::create(
                config('services.passport.login_endpoint'),
                'post'
            );
            $response = Route::dispatch($tokenRequest);

            return $response;
        } 

           
       //  return $response->getBody();
        //  catch(error $e) {return 'response($e)';}
             
    //     } catch (\GuzzleHttp\Exception\BadResponseException $e) {
    //         if($e->getCode() === 400) {

    //             return response()->json('Invaild Request. Please enter a username or a password', $e->getCode());

    //         } else if ($e->getCode() === 401){

    //             return response()->json('Invaild Request. Please enter a correct username or password', $e->getCode());

    //         }

    //         return response()->json('Something went wrong on the server', $e->getCode());
    //     }
    // }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }
}