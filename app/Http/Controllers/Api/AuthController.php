<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Hash;
use Validator;
use Illuminate\Http\Response;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\App;
Use App\Http\Requests\Api\RegistrationRequests;
use App\Http\Requests\Api\LoginRequest;
use Carbon\Carbon;
use App\Transformers\Json;
use DB;

class AuthController extends Controller
{

    /**
     * Login api
     *
     * @param Request $request
     *
     * @return Response
     */
    public function login(LoginRequest $request) {
        $user = User::where('email', $request->email)->first();

        // If the email doesn't exist, return an error message
        if (!$user) {
            return $this->sendError('User not found with this email address', [], 400);
        }
        $hashpassword = $user->password;
        if (!empty($user->password) && !Hash::check($request->password, $hashpassword)) {
            return $this->sendError('Invalid password', [], 200);
        }

        // Check if the user has an existing password
        if (empty($user->password)) {
            // If the user has no password (e.g. they are a new user or have not set it), update it
            $user->password =  Hash::make($request->password);
            $user->save();
        }
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)) {
            $user->password = Hash::make($request->password);
            $user->save();

            $token = $user->createToken('businessToken')->plainTextToken;


            $response = [
                'user' => new UserResource($user),
            ];
            return $this->sendResponse($response,$token, __('You are loged in'));
        } else {
            $errors = [
                __('These credentials do not match our record')
            ];
            return $this->sendError('Error', $errors);
        }
       
    }

    public function registration(RegistrationRequests $request)
    {
        $data= $request->all();
        if ($request->hasFile('square_icon')) {
            $imageName = uploadFileToFolder($request->file('square_icon'), 'squareIcon');
            $data['square_icon'] = $imageName;
        }
        if ($request->hasFile('background_image')) {
            $backgroundImage = uploadFileToFolder($request->file('background_image'), 'backgroundImage');
            $data['background_image'] = $backgroundImage;
        }
        $user = User::create($data);

        $token = $user->createToken('businessToken')->plainTextToken;

        $response = [
            'user' => new UserResource($user),
            
        ];

        return $this->sendResponse($response,$token, __('Data saved successfully'));
    }

    /**
     * Logout api
     *
     * @param Request $request
     *
     * @return Response
     */
    public function logout(Request $request) {
        if (Auth::User()) {
            $request->user()->currentAccessToken()->delete();
            return $this->sendResponse('','', 'User logout successfully');
        } else {
            return $this->sendError('Unable to Logout', '');
        }
    }


     /**
     * success response method.
     *
     * @param $result
     * @param $message
     *
     * @return Response
     */
    public function sendResponse($result, $sessionid = null, $message) {
        $response = [
            'token' => $sessionid,
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];
        return response()->json($response, 200);
    }

    /**
     * error response method.
     *
     * @param $result
     * @param $message
     *
     * @return Response
     */
    public function sendError(string $message, array $data = [], int $statusCode = 400)
    {
        return response()->json([
            'sessionid' => '',
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
    

}
