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
$cats =  $this->getMainCategories($token);
print_r($cats);

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


	function getMainCategories(String $token)
    {
        $getCategoriesUrl = "https://api.allegro.pl.allegrosandbox.pl/sale/categories";
    
        $ch = curl_init($getCategoriesUrl);
    
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


        $file = fopen('demosaved.csv', 'w');
 
        // save the column headers
        fputcsv($file, array('Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5'));
         
        // Sample data. This can be fetched from mysql too
        $data = array(
        array('Data 11', 'Data 12', 'Data 13', 'Data 14', 'Data 15'),
        array('Data 21', 'Data 22', 'Data 23', 'Data 24', 'Data 25'),
        array('Data 31', 'Data 32', 'Data 33', 'Data 34', 'Data 35'),
        array('Data 41', 'Data 42', 'Data 43', 'Data 44', 'Data 45'),
        array('Data 51', 'Data 52', 'Data 53', 'Data 54', 'Data 55')
        );
         
        // save each row of the data
        foreach ($data as $row)
        {
        fputcsv($file, $row);
        }
         
        // Close the file
        fclose($file);

    
        return $categoriesList;
	}
	



}
