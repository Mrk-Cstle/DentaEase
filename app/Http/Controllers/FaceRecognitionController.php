<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use CURLFile;

class FaceRecognitionController extends Controller
{
    //
 
        private $apiKey = 'd6oKAAzVLTtgRyeecdED4eHFi9wfmq3I';
        private $apiSecret = 'qe_nYezzGtNwf4WN_drOcrxfeg0ryJ7S';
        private $storedFaceToken = 'eb70acc4760484bcac5e091c58671b00'; // Replace with real one if needed

    public function getLandmarks(Request $request)
    {
        $request->validate([
            'login_face_image' => 'required|image',
        ]);
    
        // Get the uploaded image
        $image = $request->file('login_face_image');
    
        // Step 1: Get face_token from the uploaded image
        $detectResponse = Http::attach(
            'image_file', file_get_contents($image), $image->getClientOriginalName()
        )->post('https://api-us.faceplusplus.com/facepp/v3/detect', [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
        ]);
    
       
    
        // Parse the response
        $detectData = $detectResponse->json();
    
        // If no faces are detected, return an error response
        if (empty($detectData['faces'][0]['face_token'])) {
            return response()->json(['error' => 'No face detected in login image.', 'api_response' => $detectData], 400);
        }
    
        // Extract the face token from the response
        $faceToken2 = $detectData['faces'][0]['face_token'];
    
        // Step 2: Verify the face token with the stored face token
        $verifyResponse = Http::asForm()->post('https://api-us.faceplusplus.com/facepp/v3/compare', [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'face_token1' => $this->storedFaceToken,  // Stored face token from DB
            'face_token2' => $faceToken2,
        ]);
    
        // Log the verify response to inspect it (Optional)
      
    
        // Parse the verification response
        $verifyData = $verifyResponse->json();
    
        // Check the verification result and respond accordingly
        if (isset($verifyData['confidence']) && $verifyData['confidence'] > 70) {
            return response()->json(['message' => 'Login successful!', 'verify_data' => $verifyData]);
        } else {
            return response()->json(['message' => 'Login failed. Face does not match.', 'verify_data' => $verifyData], 401);
        }
    }

    
}
