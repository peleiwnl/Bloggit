<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

/**
 * A class for interacting with the news api to fetch news articles and headlines.
 */
class NewsService
{

    private static $instance = null;
    private $apiKey;
    private $baseUrl = 'https://newsapi.org/v2'; //api url

    /**
     * Initializes the api key from the application's configuration.
     */
    private function __construct()
    {
        $this->apiKey = config('services.news.api_key');
    }

    /**
     * Get the singleton instance of the news service
     *
     * @return NewsService The singleton instance of the class.
     */
    public static function getInstance(): NewsService
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Fetch top headlines from the api
     *
     * @param int $page The page number for paginated results (default is 1).
     * @param int $pageSize The number of articles to fetch per page (default is 10).
     * @return array The JSON-decoded response from the API containing the headlines.
     * @throws Exception If the API request fails or returns an error.
     */
    public function getTopHeadlines(int $page = 1, int $pageSize = 10): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/top-headlines", [
                'country' => 'us', 
                'pageSize' => $pageSize,
                'page' => $page,
                'apiKey' => $this->apiKey
            ]);

            if ($response->failed()) {
                throw new Exception('Failed to fetch news');
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception('Error fetching news: ' . $e->getMessage());
        }
    }

    /**
     * Prevent cloning of the singleton instance.
     */
    public function __clone() {}

    /**
     * Prevent unserialization of the singleton instance.
     */
    public function __wakeup() {}
}
