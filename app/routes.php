<?php

use Symfony\Component\HttpFoundation\Request;
use MicroImmo\Domain\Annonce;

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

// // Annonce details
$app->get('/annonce/{id}', function ($id) use ($app) {
	$annonce = $app['dao.annonce']->find($id);
	return $app['twig']->render('annonce.html.twig', array('annonce' => $annonce));
})->bind('annonce');


// Home page
$app->get('/', function (Request $request) use ($app) {
	function reloadData($app){
		function getUrlContents($url){
			$crl = curl_init(); // Initialise une session cURL
			$timeout = 5;
			curl_setopt ($crl, CURLOPT_URL,$url); // destination
			curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1); // retourne une str
			curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout); // timeout
			$result = curl_exec($crl); // execute
			curl_close($crl); //
			return $result;
		}
		// Retourne des objets XPath à partir de page html
		function returnXPathObject($item) {
			if(isset($item)){
				$xmlPageDom = new DomDocument(); // Instantiating a new DomDocument object
				$internalErrors = libxml_use_internal_errors(true); // set error level
				$xmlPageDom->loadHTML($item); // Loading the HTML from downloaded page
				libxml_use_internal_errors($internalErrors); // Restore error level
				$xmlPageXPath = new DOMXPath($xmlPageDom); // Instantiating new XPath DOM object
				return $xmlPageXPath; // Returning XPath object
			}
		}
		$url = "http://www.seloger.com/list.htm?ci=690386&idtt=2&idtypebien=1,2&tri=initial&bd=DetailToList_SL";
		$data = array();
		$page = getUrlContents($url);
		$pageXpath = returnXPathObject($page);
		$price = $pageXpath->query('//a[@class="amount"]');
		$url = $pageXpath->query('//a[@class="amount"]/@href');
		$title = $pageXpath->query('//div[@class="listing_infos"]/h2/a');
		$metre = $pageXpath->query('//ul[@class="property_list"]/li[last()]');
		$metreIfError = $pageXpath->query('//ul[@class="property_list"]/li[2]');
		$pieces = $pageXpath->query('//ul[@class="property_list"]/li[1]');
		$toDel = array("\u00c2", "\u00e2", "\u00AC", "Â", "â¬", "p", "chb", "\u00a0", "m²", " " );
		$toDel2 = array("\u00c2", "\u00e2", "\u00AC", "Â", "â¬", "chb", "\u00a0", "m²", " " );
		$moyenne = 0;

		// data
		if ($price->length > 0) {
			for ($i = 0; $i < $price->length; $i++) {

				// Prix
				$priceUnit = str_replace( chr( 194 ) . chr( 160 ), ' ',  $price->item($i)->nodeValue);
				$priceUnit = str_replace($toDel, "", $priceUnit);
				$data['details'][$i]['price'] = str_replace($toDel, "", $priceUnit);

				//url
				$data['details'][$i]['url'] = str_replace($toDel2, "", $url->item($i)->nodeValue);

				//title
				$the_title = $title->item($i)->nodeValue;
				$the_title = str_replace( 'Ã¨', 'è', $the_title);
				$data['details'][$i]['title'] = str_replace($toDel2, "",  $the_title);

				// Nb pièces
				$data['details'][$i]['pieces'] = str_replace($toDel, "",  $pieces->item($i)->nodeValue);

				// M^2
				$metreUnit = $metre->item($i)->nodeValue;
				$metreUnit = str_replace(",", ".", $metreUnit);
				if(strpos($metreUnit, "etg") === false) {
					$metreUnit = str_replace($toDel, "",  $metreUnit);
				} else {
					$metreUnit = str_replace($toDel, "", $metreIfError->item($i)->nodeValue);
				}
				$data['details'][$i]['metre'] = $metreUnit;

				// Prix / M^2
				$pricePerMeter = $priceUnit / $metreUnit;
				$moyenne += $pricePerMeter;
				$data['details'][$i]['pricePerMeter'] = $pricePerMeter;

				$annonce = new Annonce();
				$annonce->setTitle($data['details'][$i]['title']);
				$annonce->setPrix($data['details'][$i]['price']);
				$annonce->setSurface($data['details'][$i]['metre']);
				$annonce->setNbPieces($data['details'][$i]['pieces']);
				$annonce->setDateAjout('12-12-2012');
				$annonce->setVille('null');
				$annonce->setQuartier('null');
				$annonce->setUrl($data['details'][$i]['url']);
				$app['dao.annonce']->save($annonce);
				$app['session']->getFlashBag()->add('success', 'Success!!!.');
			}
		}
	}
	reloadData($app);
	$annonces = $app['dao.annonce']->findAll();
  return $app['twig']->render('index.html.twig', array('annonces' => $annonces));
})->bind('home');
