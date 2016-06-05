<?php
namespace App\Http\Controllers;

use App;
use App\Models\Website;
use Illuminate\Http\Response as FullResponse;
use Log;
use Request;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class DisplayController extends Controller
{
    public function show($page = 'index')
    {
        $website = Website::where(array(
            'host' => Request::server('HTTP_HOST'),
            'active' => true
        ))->first();
        
        if ($website != null && $website->existsOnDisk()) {
            // website found
            $requested_page = $website->getPagePath($page);
            $status = 200;
            
            if ($requested_page === false) {
                // If page not found, we prepare the 404
                Log::notice('Requested page ' . $requested_page . ' not found. Website: ' . $website . '.');
                $requested_page = $website->getPagePath('404');
                $status = 404;
            }
            
            if ($requested_page !== false) {
                // Page found
                $view_factory = app('Illuminate\Contracts\View\Factory');
                
                // Rendering include files (header, footer, ...) first
                $include_files = $website->getIncludeFiles();
                
                // Rendering target view then
                $view = $view_factory->file($requested_page);
                
                // Including Sitarium scope
                $crawler = new HtmlPageCrawler($view->render());
                $include_parts = $crawler->filter('include');
                foreach ($include_parts as $include_part) {
                    $key = $include_part->getAttribute("data-source");
                    if (array_key_exists($key, $include_files)) {
                        $include_data = $view_factory->file($include_files[$key])->render();
                        (new HtmlPageCrawler($include_part))->setInnerHtml($include_data);
                    }
                }
                /*
                $crawler->filter('body')->append(view('sitarium/sitarium_scope', array(
                    'editable_files' => $website->getEditableFiles(),
                    'backups' => $website->getBackups()
                ))->render());
                */
                return new FullResponse($crawler, $status);
            } else {
                // Page still not found...
                Log::error('Page ' . $requested_page . ' still not found. Website: ' . $website . '.');
                App::abort(404);
            }
        } else {
            // SITARIUM 404 Page
            Log::error('Website ' . $website . ' not found.');
            App::abort(404);
        }
    }
}
