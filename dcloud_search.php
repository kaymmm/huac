<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

// declare variables for sanity
$search_terms = $witness_type = $query = '';
// search_terms = (string) keyword search items
// witness_type = (int) type of witness (made it an int for security/validation pruposes)
// query = (string) the concatenated doccloud api request
$q = $options = [];
// q = the doccloud "q" query, stored as an array of query terms (which will be imploded with spaces when building out the api request, $query)
// options = an array holding the rest of the api request options. stored as an array for code readability and customizability (e.g. via checkboxes on the frontend)

// search options based on doccloud api
$options = [
    'data=true',
    'annotation=true',
    'per_page=100',
    'page=1',
    'mentions=3',
    'sections=true'];

// process post action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // prime the query with the group name since it will be included on all searches
    $q[] = 'group:dhpraxis';

    // get post variables + sanitize
    $witness_type = (int)$_POST['witness_type'];
    $search_terms = trim($_POST['search_terms']); // discard extraneous spaces
    $search_terms = htmlspecialchars($search_terms); // escape special characters prior to api request

    // add query terms based on post variables
    if ($witness_type == 1) {
        $q[] = '"Witness Type":Friendly';
    } elseif ($witness_type == 2) {
        $q[] = '"Witness Type":Unfriendly';
    }
    if ($search_terms <> '') {
        $q[] = $search_terms;
    }

    // build the api request string:
    $query = 'https://www.documentcloud.org/api/search.json?'; // the api request base
    $query .= implode('&', $options); // concatenate all of the options, separated by ampersands per "get"/api request specs
    $query .= '&q=' . implode(' ',$q); // add the query terms (the &q= is the "get" variable for the query)

    // setup guzzler client and submit the query
    $client = new Client();
    $response = $client->get($query);
    // store the guzzler response as a json variable
    $json = $response->json();

    // you could make use of a similar loop to iterate through the results
    // foreach ($json['documents'] as $id => $doc) {
    //     print_r($doc['data']);
    // }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>DigitalHUAC Test</title>
</head>
<body>
    <div>
    <form action="dcloud_search.php" method="post">
        <fieldset>
            <legend>Search</legend>
            <p><label for="search_terms">Keywords:</label>
                <input type="text" name="search_terms" <?php if ($search_terms <> '') echo 'value="' . $search_terms . '"'; ?>>
            <p><label for="witness_type">Witness Type:</label>
                <select name="witness_type">
                    <option value="1" <?php if ($witness_type == 1) echo 'selected'; ?>>Friendly</option>
                    <option value="2" <?php if ($witness_type == 2) echo 'selected'; ?>>Unfriendly</option>
                </select>
            </p>
            <p><input type="submit" value="submit" /></p>
        </fieldset>
    </form>
    </div>
<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
    <div>
        <em>Search string: </em><?php echo $query; ?><br>
        <em>Query items: </em><?php print_r($q); ?><br>
        <em>Search options: </em><?php print_r($options); ?><br>
        <h1>Results:</h1>
        <pre><?php print_r($json); ?></pre>
    </div>
<?php } ?>
</body>
</html>