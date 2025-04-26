<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class Facerecognition extends Controller
{
    //
    private $apiKey = 'd6oKAAzVLTtgRyeecdED4eHFi9wfmq3I';
    private $apiSecret = 'qe_nYezzGtNwf4WN_drOcrxfeg0ryJ7S';

    public function registerFace(Request $request)
    {
        $request->validate([
            'face_image' => 'required|image',
        ]);

        $image = $request->file('face_image');

        // Call Face++ detect API
        $response = Http::attach(
            'image_file',
            file_get_contents($image),
            $image->getClientOriginalName()
        )->post('https://api-us.faceplusplus.com/facepp/v3/detect', [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
        ]);

        $data = $response->json();

        // Check if face was detected
        if (empty($data['faces'][0]['face_token'])) {
            return response()->json([
                'message' => 'No face detected.',
                'api_response' => $data
            ], 400);
        }

        $faceToken = $data['faces'][0]['face_token'];
      /** @var \App\Models\User $user */
        // Save to database for current logged-in user
        $user = Auth::user(); // Make sure user is authenticated
        $user->face_token = $faceToken;
        $user->save();

        return response()->json([
            'message' => 'Face registered successfully!',
            'face_token' => $faceToken,
        ]);
    }

}
