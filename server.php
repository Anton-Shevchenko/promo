<?php
    
if(!isset($_POST['send'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $orgname = $_POST['orgname'];

    sender($fname, $lname, $email, $phone, $orgname);
}

function sender($fname, $lname, $email, $phone, $orgname) {

    define("ACTIVECAMPAIGN_URL", "https://https://antonwhitelion.api-us1.com.api-us1.com");
    define("ACTIVECAMPAIGN_API_KEY", "03cc2f72499ed1d471b8c2ef3649034838f7b833dfc7cf07445e9eea69b1482d475df80d");


    $url = 'https://mailapps.api-us1.com';


    $params = array(
        'api_key'      => '03cc2f72499ed1d471b8c2ef3649034838f7b833dfc7cf07445e9eea69b1482d475df80d',

        // this is the action that adds a contact
        'api_action'   => 'contact_add',

        'api_output'   => 'json',
    );

    // here we define the data we are posting in order to perform an update
    $post = array(
        'email'                    => $email,
        'first_name'               => $fname,
        'last_name'                => $lname,
        'phone'                    => $phone,
        'orgname'                  => $orgname,
        'tags'                     => 'api',
        //'ip4'                    => '127.0.0.1',

        // any custom fields
        //'field[345,0]'           => 'field value', // where 345 is the field ID
        //'field[%PERS_1%,0]'      => 'field value', // using the personalization tag instead (make sure to encode the key)

        // assign to lists:
        'p[123]'                   => 1, // example list ID (REPLACE '123' WITH ACTUAL LIST ID, IE: p[5] = 5)
        'status[123]'              => 1, // 1: active, 2: unsubscribed (REPLACE '123' WITH ACTUAL LIST ID, IE: status[5] = 1)
        //'form'          => 1001, // Subscription Form ID, to inherit those redirection settings
        //'noresponders[123]'      => 1, // uncomment to set "do not send any future responders"
        //'sdate[123]'             => '2009-12-07 06:00:00', // Subscribe date for particular list - leave out to use current date/time
        // use the folowing only if status=1
        'instantresponders[123]' => 1, // set to 0 to if you don't want to sent instant autoresponders
        //'lastmessage[123]'       => 1, // uncomment to set "send the last broadcast campaign"

        //'p[]'                    => 345, // some additional lists?
        //'status[345]'            => 1, // some additional lists?
    );

    // This section takes the input fields and converts them to the proper format
    $query = "";
    foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
    $query = rtrim($query, '& ');

    // This section takes the input data and converts it to the proper format
    $data = "";
    foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
    $data = rtrim($data, '& ');
 
    if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
        die('JSON not supported. (introduced in PHP 5.2.0)');
    }

    // define a final API request - GET
    $api = $url . '/admin/api.php?' . $query;

    $request = curl_init($api); // initiate curl object
    curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
    curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
    //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

    $response = (string)curl_exec($request); // execute curl post and store results in $response
    curl_close($request); // close curl object

    if ( !$response ) {
        die('Nothing was returned. Do you have a connection to Email Marketing server?');
    }


    $result = json_decode($response);

    //var_dump($result);
    if($result->{'result_message'} == 'Contact added' ) {
        echo 'SUCCESS';
    } else{
        echo 'Error';
    }
}
