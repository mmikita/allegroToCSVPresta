<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	

	public function showWelcome()
	{
$token = $this->getToken();




print_r($this->getMainProducts($token));

  //     return View::make('hello');


        

	}

function getToken(){
    $authUrl = "https://allegro.pl.allegrosandbox.pl/auth/oauth/token?grant_type=client_credentials";
    $clientId = "d20d65b102d54365a920e6f56f13a4f9";
    $clientSecret = "A7HmDIXNYiD0islMfjEDI80DRQKI9zIqn4HkDI4vr5C4PfjnbMZ9JnDXTCMAxf7L";

    $ch = curl_init($authUrl);

    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERNAME, $clientId);
    curl_setopt($ch, CURLOPT_PASSWORD, $clientSecret);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $tokenResult = curl_exec($ch);
    $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($tokenResult === false || $resultCode !== 200) {
        exit ("Something went wrong");
    }

    $tokenObject = json_decode($tokenResult);

    return $tokenObject->access_token;


}


	function getMainProducts(String $token)
    {
        $opinie = "https://api.allegro.pl.allegrosandbox.pl/offers/listing?seller.id=44292194";

        $ch = curl_init($opinie);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                     "Authorization: Bearer $token",
                     "Accept: application/vnd.allegro.public.v1+json"
        ]);
        
        $mainCategoriesResult = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($mainCategoriesResult === false || $resultCode !== 200) {
            exit ("Something went wrong");
        }
        
        $categoriesList = json_decode($mainCategoriesResult);
        
        
        return json_encode($categoriesList, JSON_PRETTY_PRINT);
	}
	



}
