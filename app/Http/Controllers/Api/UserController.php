<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Api\RegistrationRequests;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Enums\LoginType;
use App\Enums\RoleType;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function profile() {
        $user = auth('api')->user();
        $user['square_icon'] = isset($user->square_icon) && Storage::disk('public')->exists($user->square_icon) ? getUrl($user->square_icon): asset('images/avtar.png');
        $user['background_image'] = isset($user->background_image) && Storage::disk('public')->exists($user->background_image) ? getUrl($user->background_image) : asset('images/avtar.png');
        return $this->sendResponse($user, __('Retrieved successfully'));
    }

    /**
     * success response method.
     *
     * @param $result
     * @param $message
     *
     * @return Response
     */
    public function sendResponse($result, $message) {
        $response = [
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


     /**
     * Update User profile
     *
     * @param int $id
     * @param RegistrationRequests $request
     *
     * @return Response
     */
    public function updateUserProfile(RegistrationRequests $request, $id)
    {
        $data = $request->all();

        $user = User::find($id);
        $removeImage = $removeBackgroundImage = false;
        if ($request->file('square_icon')) {
            $imageName = uploadFileToFolder($request->file('square_icon'), 'squareIcon');
            $data['square_icon'] = $imageName;
            $removeImage = true;
        }
        if ($request->file('background_image')) {
            $backgroundImage = uploadFileToFolder($request->file('background_image'), 'backgroundImage');
            $data['background_image'] = $backgroundImage;
            $removeBackgroundImage = true;
        }
        if ($removeImage && !empty($user->square_icon)) {
           removeFile($user->square_icon);
        }
        if ($removeBackgroundImage && !empty($user->background_image)) {
            removeFile($user->background_image);
         }
        if ($user->update($data)) {
            return $this->sendResponse($user, __('Profile updated successfully'));
        } else {
            return $this->sendError('Unable to update profile', '');
        }
    }
}
