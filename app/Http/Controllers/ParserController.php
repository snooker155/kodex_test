<?php namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Parse;


class ParserController extends Controller {

	public function index(){

	}



	public function curl (){

		$html = new \Htmldom('http://spb.hh.ru/search/vacancy?text=PHP%2FRuby&page=0');

		//$elements = array();
		$results = array();

		$rc = new \RollingCurl\RollingCurl();
		$rc -> get('http://spb.hh.ru/search/vacancy?text=PHP%2FRuby&page=0');


		foreach ($html->find('div [class=b-pager m-pager_left-pager HH-Pager]', 0)->children(2)->children as $hreflist) {
		 	$href = $hreflist->children(0)->href;
		 	$rc->get('http://spb.hh.ru/'.$href);
		}

		$start = microtime(true);
		echo "Fetching..." . PHP_EOL . "</br>";

		$rc
	    ->setCallback(function(\RollingCurl\Request $request, \RollingCurl\RollingCurl $rc) use (&$results) {

	    	$elements = array();
	    	$html2 = new \Htmldom($request->getResponseText());
	    	foreach ($html2->find('a[class=search-result-item__name]') as $href_i){
				$vacancy['href'] = $href_i->href;
				$elements[] = $vacancy;
			}    

	        // Clear list of completed requests and prune pending request queue to avoid memory growth
	        $rc->clearCompleted();
	        $rc->prunePendingRequestQueue();
	        echo "Fetch complete for (" . $request->getUrl() . ")" . PHP_EOL . "</br>";

	        $rollingCurl = new \RollingCurl\RollingCurl();

				foreach ($elements as $element) {
			    	$rollingCurl->get($element['href']);
				}


				$start = microtime(true);
				echo "</br>Fetching..." . PHP_EOL . "</br>";
				$rollingCurl
			    ->setCallback(function(\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$results) {


			    	$job_page = new \Htmldom($request->getResponseText());
			    	preg_match('/(\d+)/s', $request->getUrl(), $url_id);
			    	$item['id'] = $url_id[1];
			    	if (null !== $job_page->find('h1.title')){
						$item['title'] = $job_page ->find('h1.title', 0)->plaintext;
					}else{
						$item['title'] = null;
					}
					if (null !== $job_page ->find('div.companyname', 0)){
						$item['company'] = $job_page ->find('div.companyname', 0)->plaintext;
					}else{
						$item['company'] = null;
					}				
					if (null !== $job_page ->find('td.l-content-colum-1', 0)){
						$item['salary'] = $job_page ->find('td.l-content-colum-1', 0)->plaintext;
					}else{
						$item['salary'] = null;
					}
					if (null !== $job_page ->find('td.l-content-colum-2', 0)){
						$item['city'] = $job_page ->find('td.l-content-colum-2', 0)->plaintext;
					}else{
						$item['city'] = null;
					}
					if (null !== $job_page ->find('td.l-content-colum-3', 0)){
						$item['experience'] = $job_page ->find('td.l-content-colum-3', 0)->plaintext;
					}else{
						$item['experience'] = null;
					}
					if (null !== $job_page ->find('div.b-vacancy-desc-wrapper', 0)){
						$item['description'] = $job_page ->find('div.b-vacancy-desc-wrapper', 0)->plaintext;
					}else{
						$item['description'] = null;
					}
					if (null !== $job_page ->find('span[itemprop=employmentType]', 0)){
						$item['type_of_job'] = $job_page ->find('span[itemprop=employmentType]', 0)->plaintext;
					}else{
						$item['type_of_job'] = null;
					}
					if (null !== $job_page ->find('div.vacancy-address-text', 0)){
						$item['address'] = $job_page ->find('div.vacancy-address-text', 0)->plaintext;
					}else{
						$item['address'] = null;
					}
					if (null !== $job_page ->find('div.vacancy-sidebar', 0)){
						$item['date_of_publicity'] = $job_page ->find('div.vacancy-sidebar', 0)->plaintext;
					}else{
						$item['date_of_publicity'] = null;
					}
					$results[] = $item;

					
					if (!Parse::get($item['id'])){

						Parse::add($item); 

					}
			     
			    
			        $rollingCurl->clearCompleted();
			        $rollingCurl->prunePendingRequestQueue();

			        echo "Fetch complete for (" . $request->getUrl() . ")" . PHP_EOL . "</br>";
			    })
			    ->setSimultaneousLimit(35)
			    ->execute();
				;
				echo "...done in " . (microtime(true) - $start) . PHP_EOL . "</br>";

				echo "All results: " . PHP_EOL. "</br>";

	    })
	    ->setSimultaneousLimit(10)
	    ->execute();
		;
		echo "...done in " . (microtime(true) - $start) . PHP_EOL . "</br>";


		//return $results;


		//echo "All results: " . PHP_EOL;
		//print_r ($elements);
		//print_r ($results);
		return $results;
		//return $elements;



	}
}
