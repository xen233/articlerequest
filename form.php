<?php
// echo '<link rel="stylesheet" href="app.css"><script src="pace.js"></script>';

function callAPI($method, $url, $data){
    $curl = curl_init();
 
    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
          break;
       default:
          if ($data)
             $url = sprintf("%s?%s", $url, http_build_query($data));
    }
 
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'APIKEY: 111111111111111111111',
       'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
 }
$xs2 = isset($_GET['id']) ? $_GET['id'] : '';
$pmid = substr($xs2,5);

if (strlen($xs2) <6) {
    header('Location: /');
    } else {
        $checkOvid = callAPI('GET', 'https://4044172.odslr.com/resolver/full?sid=Entrez:PubMed&rft_id=pmid:' . $pmid, false);

    if (strpos($checkOvid, 'This item is not part of your institutional holdings') == null) {
        // echo strpos($checkOvid, 'This item is not part of your institutional holdings');
        header('Location: https://4044172.odslr.com/resolver/full?sid=Entrez:PubMed&rft_id=pmid:' . $pmid);
    } else {
$eutilsapi = new \SendGrid(getenv('PUBMED_API_KEY'));

        $get_data = callAPI('GET', 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&id=' . $pmid . '&retmode=json&api_key=' . $eutilsapi, false);
        $pmurlpi = 'https://www.ncbi.nlm.nih.gov/pubmed/' . $pmid;

        $response = json_decode($get_data, true);
        // $errors = $response['response']['errors'];
        // $data = $response['response']['data'][0];

        $titleapi = '';
        $tauthorsapi = '';
        $journalapi = '';
        $yearapi = '';
        $volumeapi = '';
        $issueapi = '';
        $issueapi = '';


        $titleapi = isset($response['result'][$pmid]['title']) ? $response['result'][$pmid]['title'] : '';
        $tauthorsapi = '';
        foreach($response['result'][$pmid]['authors'] as $looped_value){
        $tauthorsapi .=$looped_value['name'] . ', ';
        $journalapi = isset($response['result'][$pmid]['fulljournalname']) ? $response['result'][$pmid]['fulljournalname'] : '';
        $yearapi = isset($response['result'][$pmid]['pubdate']) ? $response['result'][$pmid]['pubdate'] : '';
        $volumeapi = isset($response['result'][$pmid]['volume']) ? $response['result'][$pmid]['volume'] : '';
        $issueapi = isset($response['result'][$pmid]['issue']) ? $response['result'][$pmid]['issue'] : '';
        $issueapi = isset($response['result'][$pmid]['pages']) ? $response['result'][$pmid]['pages'] : '';


        // $get_library = file_get_contents('libraries.json');
        // $libraries = json_decode($get_library, true);
        // $library = isset($_GET['lib']) ? $_GET['lib'] : '';
        // $request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
        // $get_library = file_get_contents('libraries.json');
        // $libraries = json_decode($get_library, true);
        // $xs = substr($request_uri[0], 1);
        // $libnameapi = $libraries[$library]['name'];
        // $libemalapi = $libraries[$library]['email'];
        // $libtelapi = $libraries[$library]['telephone'];

        // debugging
        // echo $value;
        // echo $response;
        // print_r ($libraries);
        // echo $libnameapi;
        // echo $libemalapi;
        // echo $libtelapi;

        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Request an article</title>
</head>
<!-- <body class="bg-light" onload="getRequest()"> -->
<body class="bg-light">
    <div class="container">
        <h1>Request an article</h1>
        <!-- <div id="wait">
            <p>please wait...</p>
        </div> -->
        <p>The article details below will be sent to your library, please make sure they are all correct and enter your name and email at the bottom.</p>
        <div class="initial-hide" id="requestsheet">
            <form action="sendmail.php" method="post">
            <div class="col-md-12">
                <h4 class="mb-3">Article details</h4>

                    <div class="form-group">
                        <label for="title">Article title:</label>
                        <input class="form-control" name="title" id="title" type="text" value="<?php echo $titleapi ?>" readonly required>
                        <label for="authors">Authors:</label>
                        <input class="form-control" name="authors" id="authors" type="text" value="<?php echo $tauthorsapi ?>" readonly required>
                        <!-- <label for="journal">Journal:</label>
                        <input class="form-control" name="journal" id="journal" type="text" value="<?php echo $journalapi ?>" readonly required> -->
                        <div class="form-group row">
                            <div class="col col-sm-7">
                                <label for="journal">Journal:</label>
                                <input class="form-control" name="journal" id="journal" type="text" value="<?php echo $journalapi ?>" readonly required>
                            </div>
                            <div class="col col-sm-2">
                                <label for="year">Year:</label>
                                <input class="form-control" name="year" id="year" type="text" value="<?php echo $yearapi ?>" readonly required>
                            </div>
                            <div class="col col-sm-1">
                                <label for="volume">Volume:</label>
                                <input class="form-control" name="volume" id="volume" type="text" value="<?php echo $volumeapi ?>" readonly required>
                            </div>
                            <div class="col col-sm-1">
                                <label for="issue">Issue:</label>
                                <input class="form-control" name="issue" id="issue" type="text" value="<?php echo $issueapi ?>" readonly required>
                            </div>
                            <div class="col col-sm-1">
                                <label for="pages">Pages:</label>
                                <input id="pages" name="pages" class="form-control" type="text" value="<?php echo $issueapi ?>" readonly required>
                            </div>
                        </div>

                        <label for="pmurl" hidden>URL on PubMed:</label>
                        <input class="form-control" name="pmurl" id="pmurl" type="text" value="<?php echo $pmurlpi ?>" readonly required hidden>
                    </div>
            </div>
            <hr />
                    <div class="col-md-12 order-md-1">
                    <h4 class="mb-3">Your request will be sent to this library. If this is not correct, please contact your library</h4>
                        <div class="form-group row">
                            <label for="libname" class="col-sm-2 col-form-label">Library: </label>
                            <div class="col-sm-9">
                                <input class="form-control-plaintext" name="libname" id="libname" type="text" value="<?php echo $libnameapi ?>" readonly required>
                            </div>
                        </div>
                        <div class="form-group row">            
                            <label for="libemal" class="col-sm-2 col-form-label">Email: </label>
                            <div class="col-sm-9">
                                <input class="form-control-plaintext" name="libemal" id="libemal" type="text" value="<?php echo $libemalapi ?>" readonly required>
                            </div>
                        </div>
                        <div class="form-group row">            
                            <label for="libtel" class="col-sm-2 col-form-label">Contact number: </label>
                            <div class="col-sm-9">
                                <input class="form-control-plaintext" name="libtel" id="libtel" type="text" value="<?php echo $libtelapi ?>" readonly required>
                            </div>
                        </div>
                    </div>

                    <hr />
            <div class="col-md-12">

                <h4 class="mb-3">Your details</h4>

                <div class="form-group row">
                    <label for="username" class="col-sm-2 col-form-label">Your name:</label>
                    <div class="col-sm-9">
                        <input id="username" name="username" class="form-control" type="text" placeholder="Enter name (required)" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="useremail" class="col-sm-2 col-form-label">Your email:</label>
                    <div class="col-sm-9">
                        <input type="email" name="useremail" class="form-control" id="useremail" placeholder="Enter email (required)" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="phonenumber" class="col-sm-2 col-form-label">Phone number:</label>
                    <div class="col-sm-9">
                        <input id="phonenumber" name="phonenumber" class="form-control" type="text" placeholder="Enter phonenumber (not required)" >
                    </div>
                </div>
                <label for="message">Please type any specific instructions or notes for the library:</label>
                <textarea class="form-control" id="message" rows="3"></textarea>
                <code>The information submitted in this form will only be used to keep you updated with the status of your request. This site will not log or retain any information put through it. <strong>Please note: your library may charge you for requesting articles</strong>, please contact your library for more information.</code><br />
                <code>If you are happy with this, please tick the box below</code>
                <div class="form-check">
                    <input class="form-check-input" name="consent" type="checkbox" id="consent" value="" required>
                    <label class="form-check-label" for="consent">I agree   </label>

                </div>

                <input type="submit" class="btn btn-primary mb-2" value="Request article" />
            </div>
            </form>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

</body>

</html>