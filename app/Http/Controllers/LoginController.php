<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use App\Helpers\WaferBaseHelp;

class LoginController extends Controller
{
	public function __construct()
	{
		$this->globHelper = new WaferBaseHelp();
	}

    protected function generateToken($id)
    {
    	$key = env('APP_KEY');
    	$sub = $this->globHelper->encryptGlobal($id, 'jij!HeReasfwn13');
    	$payload = [
    		'iss' => Str::random(10), 	  // issuer of the token
    		'sub' => $sub,		  	  	  // subject of the token
    		'iat' => time(),	  	  	  // time when JWT was isshued
    		'exp' => time() + 60 * 60	  // Expiration time ditambah 3600 detik
    	];
    	return JWT::encode($payload, $key);
    }

    public function submit(Request $req)
    {

    	$cust = User::where('email', $req->email);
    	if ($cust->count() > 0) {
    		$getCust = $cust->first();
    		if (Hash::check($req->password, $getCust->password)) {
    			
    			$token = $this->generateToken($getCust->id);
    			$getCust->api_token = $token;
    			$getCust->save();

    			$callback = array('success' => true, 'message' => 'Success login!', 'token' => $token);
    			$statusCode = 200;

    		} else {
    			$callback = array('success' => false, 'message' => 'Password does not match!');
    			$statusCode = 400;
    		}
    	} else {
    		$callback = array('success' => false, 'message' => 'Email does not exist!');
    		$statusCode = 400;
    	}

    	return response()->json($callback, $statusCode);
    }
} 