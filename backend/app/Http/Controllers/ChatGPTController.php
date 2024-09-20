<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatGPTController extends Controller
{
    public function chat(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/completions', [
            'model' => 'text-davinci-003', // Hoặc model khác tùy thuộc vào API mà bạn đang sử dụng
            'prompt' => $request->input('prompt'),
            'max_tokens' => 150,
            'temperature' => 0.5,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);

        if ($response->successful()) {
            $chatResponse = $response->json()['choices'][0]['text'] ?? 'Sorry, I did not understand that.';
            return response()->json(['message' => $chatResponse]);
        } else {
            return response()->json([
                'error' => 'Failed to get response from ChatGPT.',
                'status' => $response->status(),
                'details' => $response->json()
            ], $response->status());
        }
    }
}
