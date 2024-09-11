<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Goutte\Client;
use Illuminate\Support\Facades\Log;

class TextGenerationController extends Controller
{
    // Show the form for generating text
    public function showForm()
    {
        return view('generate-form'); // Returns the form view
    }

    // Handle form submission, scrape news, and process with GPT-4
    // public function generate(Request $request)
    // {
    //     $request->validate([
    //         'keyword' => 'required|string',
    //         'year' => 'required|numeric',
    //     ]);
    
    //     $keyword = $request->input('keyword');
    //     $year = $request->input('year');
    
    //     // Scrape Indonesian news based on keyword and year
    //     $newsArticles = $this->scrapeKompasNews($keyword, $year);
    
    //     // Prepare text for GPT-4 processing, including article title, description, and URL
    //     $articlesText = '';
    //     foreach ($newsArticles as $article) {
    //         $articlesText .= $article['title'] . ": " . $article['description'] . "\n" .
    //                          "Link: " . $article['link'] . "\n\n";
    //     }
    
    //     // Define the prompt for GPT-4 Turbo
    //     $prompt = "Ringkas berita utama di Indonesia pada tahun $year tentang '$keyword'. 
    //                Berikut adalah beberapa artikel terkait yang ditemukan:\n\n$articlesText.
    //                Silakan sertakan sumber artikel tersebut di akhir ringkasan.\n\n";
    
    //     // Call GPT-4 API
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
    //     ])->post('https://api.openai.com/v1/chat/completions', [
    //         'model' => 'gpt-4-turbo',
    //         'messages' => [
    //             ['role' => 'system', 'content' => 'You are a helpful assistant.'],
    //             ['role' => 'user', 'content' => $prompt],
    //         ],
    //     ]);
    
    //     // Handle the GPT-4 response
    //     $result = $response->json();
    
    //     // Handle error in API response
    //     if (isset($result['error'])) {
    //         return back()->withErrors(['error' => 'There was an error with the API.']);
    //     }
    
    //     // Display the result in the view
    //     $generatedText = $result['choices'][0]['message']['content'];

    //     Log::info('Scraped News Articles:', $newsArticles); // Log articles to the Laravel log file

    
    //         // Pass both the generated text and the news articles to the view
    //     return view('generated-result', ['text' => $generatedText, 'newsArticles' => $newsArticles]);
    // }
    

    public function generate(Request $request)
    {
        // Validate the request
        $request->validate([
            'keyword' => 'required|string',
            'year' => 'required|integer|min:2015|max:' . date('Y'),
        ]);

        // Retrieve the keyword and year from the request
        $keyword = $request->input('keyword');
        $year = $request->input('year');

        // Initialize the Goutte client
        $client = new Client();

        // Kompas search URL (replace with the actual search query format)
        $url = "https://search.kompas.com/search?q=" . urlencode($keyword) . "&year=" . $year;

        // Make a GET request to the search URL
        $crawler = $client->request('GET', $url);

        // Initialize an empty array to store news data
        $news = [];

        // Scrape the titles and links from the search results
        $crawler->filter('a.gs-title')->each(function ($node) use (&$news) {
            $title = $node->text(); // Get the title text
            $link = $node->attr('href'); // Get the href link attribute

            // Store the title and link in the news array
            $news[] = [
                'title' => $title,
                'link' => $link,
            ];
        });

        // Return the news data to the Blade view
        return view('generated-result', compact('news'));
    }


    // Web scraping function for Kompas news
    public function scrapeKompasNews($keyword, $year)
    {
        $client = new Client();
        $url = "https://www.kompas.com/search?q=$keyword&year=$year";
    
        $crawler = $client->request('GET', $url);
    
        // Scrape the news titles and descriptions
        $news = [];
        $crawler->filter('.gsc-webResult')->each(function ($node) use (&$news) {
            // Adjust selectors based on the current HTML structure
            $title = $node->filter('.gs-title a')->count() ? $node->filter('.gs-title a')->text() : 'No title found';
            
            $description = $node->filter('.gsc-webResult .gs-bidi-start-align')->count() ? $node->filter('.gsc-webResult .gs-bidi-start-align')->text() : 'No description found';

            $link = $node->filter('.gs-title a')->count() ? $node->filter('.gs-title a')->attr('href') : 'No link found';

            $news[] = [
                'title' => $title,
                'description' => $description,
                'link' => $link,
            ];
        });

        Log::info('Scraped News Articles:', $news); // Log for debugging

        return $news;
    }

    // public function scrapp(Request $request)
    // {
    //     // Validate the form input
    //     $request->validate([
    //         'keyword' => 'required|string',
    //         'year' => 'required|integer|min:2015|max:' . date('Y'),
    //     ]);

    //     // Retrieve keyword and year from request
    //     $keyword = $request->input('keyword');
    //     $year = $request->input('year');

    //     // Initialize Goutte client
    //     $client = new Client();
        
    //     // Define the target URL (adjust to match the site structure)
    //     $url = "https://www.kompas.com/search?q=$keyword&year=$year"; // Adjust the URL if necessary
        
    //     // Request the page
    //     $crawler = $client->request('GET', $url);
        
    //     // Filter the content based on the keyword and year
    //     $newsData = $crawler->filter('.article__title')->each(function ($node) {
    //         return $node->text();
    //     });

    //     // Pass the scraped data to the Blade view
    //     return view('news', ['newsData' => $newsData]);
    // }
    
}
