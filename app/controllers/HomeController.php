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






        $file = fopen('demosaved.csv', 'w');
 
        // save the column headers
        fputcsv($file, array('Product ID', 'Active (0/1)', 'Name', 'Categories (x,y,z...)', 'Price tax included'
    ,'Tax rules ID', 'Wholesale price','On sale (0/1)','Discount amount','Discount percent'
,'Discount from (yyyy-mm-dd)','Discount to (yyyy-mm-dd)','Reference #', 'Supplier reference #', 'Supplier', 'Manufacturer'
,'EAN13', 'UPC', 'Ecotax', 'Width', 'Height', 'Depth', 'Weight', 'Delivery time of in-stock products', 
'Delivery time of out-of-stock products with allowed orders','Quantity', 'Minimal quantity', 'Low stock level'
,'Send me an email when the quantity is under this level', 'Visibility', 'Additional shipping cost'
,'Unity', 'Unit price', 'Summary', 'Description', 'Tags (x,y,z...)', 'Meta title', 'Meta keywords', 'Meta description'
,'URL rewritten', 'Text when in stock', 'Text when backorder allowed', 'Available for order (0 = No, 1 = Yes)','Product available date'
,'Product creation date', 'Show price (0 = No, 1 = Yes)', 'Image URLs (x,y,z...)','Image alt texts (x,y,z...)', 'Delete existing images (0 = No, 1 = Yes)'
,'Feature(Name:Value:Position)', 'Available online only (0 = No, 1 = Yes)', 'Condition', 'Customizable (0 = No, 1 = Yes)', 'Uploadable files (0 = No, 1 = Yes)'
,'Text fields (0 = No, 1 = Yes)', 'Out of stock action', 'Virtual product', 'File URL', 'Number of allowed downloads', 'Expiration date'
,'Number of days', 'Number of days', 'Advanced stock management', 'Depends On Stock', 'Warehouse', 'Acessories  (x,y,z...)'));
         

$token = $this->getToken();


$resonse = $this->getMainProducts($token);
$obj = json_decode($resonse, true);



$obj2 =  $obj['items'];


$data = array();

foreach($obj2['regular'] as $item) {
  
    $category = $this->getCategoryById($token, $item['category']['id']);
    $cat = json_decode($category, true);
    $categories = $cat['name'];
while(1){
    $category = $this->getCategoryById($token, $cat['parent']['id']);
    $cat = json_decode($category, true);
    if($cat['parent']['id'] ==''){
        $categories = $cat['name']."|". $categories;
    break;
        }   
      //  print("<pre>".print_r($cat,true)."</pre>");
        $categories = $cat['name']."|". $categories;
    }
    array_push($data, array($item['id'], '1', $item['name'], $categories, 'Data 15'));


}
         
        // save each row of the data
        foreach ($data as $row)
        {
        fputcsv($file, $row);
        }
         
        // Close the file
        fclose($file);

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
     //   $opinie = "https://api.allegro.pl.allegrosandbox.pl/offers/listing?seller.id=44292194";
  $opinie = "https://api.allegro.pl.allegrosandbox.pl/offers/listing?seller.id=44090896";

      

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
        
        
        return $mainCategoriesResult;
	}
    
    function getCategoryById(String $token, String $category)
    {
        $category = "https://api.allegro.pl.allegrosandbox.pl/sale/categories/".$category;
        $ch = curl_init($category);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                     "Authorization: Bearer $token",
                     "Accept: application/vnd.allegro.public.v1+json"
        ]);
        
        $categoryResp = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($categoryResp === false || $resultCode !== 200) {
            exit ("Something went wroner " . $resultCode . $categoryResp);
        }
     
        
        return $categoryResp;
	}



}
