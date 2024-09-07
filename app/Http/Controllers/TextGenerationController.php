<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TextGenerationController extends Controller
{
    // Show the form for text generation
    public function showForm()
    {
        return view('generate-form'); // Load the form view
    }

    // Handle text generation from user input
    public function generate(Request $request)
    {
        // Validate the form input
        $request->validate([
            'keyword' => 'required|string',
            'year' => 'required|numeric',
        ]);
    
        // Get the keyword and year from the form
        $keyword = $request->input('keyword');
        $year = $request->input('year');
    
        // Log the API key to verify it's correctly loaded
        Log::info(env('OPENAI_API_KEY')); // This line logs the key (remove later for security)

        // Define the prompt for the ChatGPT API
        $prompt = "Please summarize the main news in Indonesia about '$keyword' in the year $year.";

        // Fetch the API key from config/services.php
        $apiKey = config('services.openai.api_key');
        Log::info($apiKey); // Log the API key to verify (remove this later)

        // Call the OpenAI API to generate the text
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.api_key'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',  // Use GPT-3.5-turbo model
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        // Log the response for debugging purposes
        Log::info($response->json());

        // Extract the generated text from the API response
        $result = $response->json();
        $generatedText = $result['choices'][0]['message']['content'] ?? 'No content was generated.';

        // Return the view with the generated text
        return view('generated-result', ['text' => $generatedText]);
    }
}
