<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * News controller handles requests for fetching and displaying news articles with pagination
 * and filtering of invalid or removed content.
 */
class NewsController extends Controller
{

    private NewsService $newsService;

    /**
     * Create a new NewsController instance.
     * 
     * @param NewsService $newsService The service for fetching news data
     */
    public function __construct(NewsService $newsService) //dependency injection
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a paginated list of top news headlines.
     *
     * @param Request $request The incoming HTTP request
     * @return \Illuminate\View\View The news index view with articles or error
     * 
     * @throws \Exception When unable to fetch or process news data
     */
    public function index(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $perPage = 10; 
            
            $news = $this->newsService->getTopHeadlines($page);

            $filteredArticles = collect($news['articles'] ?? [])
                ->filter(function($article) {
                    $title = strtolower($article['title'] ?? '');
                    $description = strtolower($article['description'] ?? '');
                    $url = strtolower($article['url'] ?? '');
                    
                    $removedPatterns = [ //removing "removed" articles that dont display on the page
                        '[removed]',
                        'removed.com',
                        '[deleted]'
                    ];
                    
                    foreach ($removedPatterns as $pattern) {
                        if (str_contains($title, $pattern) || 
                            str_contains($description, $pattern) || 
                            str_contains($url, $pattern)) {
                            return false;
                        }
                    }
                    
                    return 
                        isset($article['title']) &&
                        isset($article['description']) &&
                        !empty(trim($article['title'])) &&
                        !empty(trim($article['description']));
                })
                ->values();

          
            $paginator = new LengthAwarePaginator(
                $filteredArticles,
                $news['totalResults'] ?? count($filteredArticles),
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query()
                ]
            );
    
            return view('news.index', [
                'articles' => $paginator,
                'totalResults' => $news['totalResults'] ?? count($filteredArticles)
            ]);
        } catch (\Exception $e) {
            return view('news.index', [
                'articles' => [],
                'error' => $e->getMessage()
            ]);
        }
    }
}