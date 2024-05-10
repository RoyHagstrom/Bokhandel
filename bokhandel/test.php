<?php
$startTime = microtime(true);


include 'class.user.php';
include 'db_connection.php';



function displayIfNotEmpty($key, $label) {
    if(isset($_SERVER[$key]) && !empty($_SERVER[$key])) {
        echo $label . $_SERVER[$key] . "<br>";
    }
}

displayIfNotEmpty('SERVER_ADDR', 'Server IP address: ');
displayIfNotEmpty('SERVER_NAME', 'Server name: ');
displayIfNotEmpty('SERVER_SOFTWARE', 'Server software: ');
displayIfNotEmpty('SERVER_ADMIN', 'Server administrator: ');
displayIfNotEmpty('SERVER_PORT', 'Server port: ');
displayIfNotEmpty('SERVER_PROTOCOL', 'Server protocol: ');
displayIfNotEmpty('DOCUMENT_ROOT', 'Document root: ');
displayIfNotEmpty('REMOTE_ADDR', 'Remote IP address: ');
displayIfNotEmpty('REMOTE_PORT', 'Remote port: ');
displayIfNotEmpty('REQUEST_METHOD', 'Request method: ');
displayIfNotEmpty('REQUEST_URI', 'Request URI: ');
displayIfNotEmpty('SCRIPT_FILENAME', 'Script filename: ');
displayIfNotEmpty('SCRIPT_NAME', 'Script name: ');
displayIfNotEmpty('QUERY_STRING', 'Query string: ');
displayIfNotEmpty('HTTP_HOST', 'HTTP Host: ');
displayIfNotEmpty('HTTP_USER_AGENT', 'HTTP User Agent: ');
displayIfNotEmpty('HTTP_ACCEPT', 'HTTP Accept: ');
displayIfNotEmpty('HTTP_ACCEPT_LANGUAGE', 'HTTP Accept Language: ');
displayIfNotEmpty('HTTP_ACCEPT_ENCODING', 'HTTP Accept Encoding: ');
displayIfNotEmpty('HTTP_CONNECTION', 'HTTP Connection: ');
displayIfNotEmpty('HTTP_CACHE_CONTROL', 'HTTP Cache Control: ');
displayIfNotEmpty('HTTP_UPGRADE_INSECURE_REQUESTS', 'HTTP Upgrade Insecure Requests: ');
displayIfNotEmpty('HTTP_PRAGMA', 'HTTP Pragma: ');
displayIfNotEmpty('HTTP_REFERER', 'HTTP Referer: ');
displayIfNotEmpty('HTTP_SEC_FETCH_SITE', 'HTTP Sec Fetch Site: ');
displayIfNotEmpty('HTTP_SEC_FETCH_MODE', 'HTTP Sec Fetch Mode: ');
displayIfNotEmpty('HTTP_SEC_FETCH_USER', 'HTTP Sec Fetch User: ');
displayIfNotEmpty('HTTP_SEC_FETCH_DEST', 'HTTP Sec Fetch Dest: ');
displayIfNotEmpty('HTTP_ACCEPT_CHARSET', 'HTTP Accept Charset: ');
displayIfNotEmpty('HTTP_SEC_CH_UA_PLATFORM', 'HTTP Sec CH UA Platform: ');
displayIfNotEmpty('HTTP_SEC_CH_UA', 'HTTP Sec CH UA: ');
displayIfNotEmpty('HTTP_SEC_CH_UA_ARCH', 'HTTP Sec CH UA Arch: ');
displayIfNotEmpty('HTTP_SEC_CH_UA_MOBILE', 'HTTP Sec CH UA Mobile: ');
displayIfNotEmpty('HTTP_SEC_CH_UA_PLATFORM_MOBILE', 'HTTP Sec CH UA Platform Mobile: ');
displayIfNotEmpty('SERVER_SIGNATURE', 'Server signature: ');
displayIfNotEmpty('SERVER_ROOT', 'Server root: ');
displayIfNotEmpty('SERVER_PROTOCOL', 'Server protocol: ');
displayIfNotEmpty('SERVER_ADMIN', 'Server admin: ');
displayIfNotEmpty('SERVER_PORT', 'Server port: ');
displayIfNotEmpty('DOCUMENT_ROOT', 'Server document root: ');
displayIfNotEmpty('REQUEST_SCHEME', 'Server request scheme: ');
displayIfNotEmpty('CONTEXT_DOCUMENT_ROOT', 'Server context document root: ');
displayIfNotEmpty('GATEWAY_INTERFACE', 'Server gateway interface: ');
displayIfNotEmpty('SERVER_PROTOCOL', 'Server protocol version: ');
displayIfNotEmpty('REQUEST_TIME', 'Server request time: ');
displayIfNotEmpty('REQUEST_TIME_FLOAT', 'Server request time float: ');
displayIfNotEmpty('TZ', 'Server timezone: ');
displayIfNotEmpty('SCRIPT_FILENAME', 'Server script filename: ');
displayIfNotEmpty('SCRIPT_NAME', 'Server script name: ');
displayIfNotEmpty('SCRIPT_URI', 'Server script uri: ');
displayIfNotEmpty('SCRIPT_URL', 'Server script url: ');
displayIfNotEmpty('PHP_SELF', 'Server php self: ');
displayIfNotEmpty('QUERY_STRING', 'Server query string: ');
displayIfNotEmpty('REQUEST_URI', 'Server request uri: ');
displayIfNotEmpty('DOCUMENT_URI', 'Server document uri: ');
displayIfNotEmpty('PHP_AUTH_DIGEST', 'Server php auth digest: ');
displayIfNotEmpty('PHP_AUTH_USER', 'Server php auth user: ');
displayIfNotEmpty('PHP_AUTH_PW', 'Server php auth pw: ');
displayIfNotEmpty('AUTH_TYPE', 'Server auth type: ');
displayIfNotEmpty('PATH_INFO', 'Server path info: ');
displayIfNotEmpty('ORIG_PATH_INFO', 'Server orig path info: ');
displayIfNotEmpty('HTTP_COOKIE', 'Server http cookie: ');
displayIfNotEmpty('HTTP_ACCEPT', 'Server http accept: ');
displayIfNotEmpty('HTTP_ACCEPT_CHARSET', 'Server http accept charset: ');
displayIfNotEmpty('HTTP_ACCEPT_ENCODING', 'Server http accept encoding: ');
displayIfNotEmpty('HTTP_ACCEPT_LANGUAGE', 'Server http accept language: ');
displayIfNotEmpty('HTTP_CONNECTION', 'Server http connection: ');
displayIfNotEmpty('HTTP_HOST', 'Server http host: ');
displayIfNotEmpty('HTTP_REFERER', 'Server http referer: ');
displayIfNotEmpty('HTTP_USER_AGENT', 'Server http user agent: ');
displayIfNotEmpty('HTTP_X_FORWARDED_FOR', 'Server http x forwarded for: ');
displayIfNotEmpty('HTTP_X_FORWARDED_HOST', 'Server http x forwarded host: ');
displayIfNotEmpty('HTTP_X_FORWARDED_PROTO', 'Server http x forwarded proto: ');
displayIfNotEmpty('HTTP_X_FORWARDED_SERVER', 'Server http x forwarded server: ');
displayIfNotEmpty('REMOTE_ADDR', 'Server remote addr: ');
displayIfNotEmpty('REMOTE_HOST', 'Server remote host: ');
displayIfNotEmpty('REMOTE_PORT', 'Server remote port: ');
displayIfNotEmpty('REMOTE_USER', 'Server remote user: ');
displayIfNotEmpty('REDIRECT_REMOTE_USER', 'Server redirect remote user: ');
displayIfNotEmpty('AUTH_TYPE', 'Server auth type: ');
displayIfNotEmpty('PATH_TRANSLATED', 'Server path translated: ');
displayIfNotEmpty('REQUEST_METHOD', 'Server request method: ');
displayIfNotEmpty('REQUEST_SCHEME', 'Server request scheme: ');
displayIfNotEmpty('REQUEST_TIME', 'Server request time: ');
displayIfNotEmpty('REQUEST_TIME_FLOAT', 'Server request time float: ');
displayIfNotEmpty('QUERY_STRING', 'Server request query string: ');
displayIfNotEmpty('DOCUMENT_ROOT', 'Server request document root: ');
displayIfNotEmpty('REQUEST_URI', 'Server request uri: ');
displayIfNotEmpty('SCRIPT_FILENAME', 'Server request script filename: ');
displayIfNotEmpty('SCRIPT_NAME', 'Server request script name: ');
displayIfNotEmpty('PHP_SELF', 'Server request php self: ');
displayIfNotEmpty('argv', 'Server request argv: ');
displayIfNotEmpty('argc', 'Server request argc: ');


echo "<h2>Performance Information</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PHP Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . " seconds<br>";


displayIfNotEmpty('argc', 'Server request argc: ');

echo "<h2>Performance Information</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PHP Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . " seconds<br>";


$endTime = microtime(true);
$executionTime = $endTime - $startTime;
echo "'Page generated in " . number_format($executionTime, 4) . " seconds.'<br>";

$dir = "images/";
$images = glob($dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

usort($images, function($a, $b) {
    return filesize($a) <=> filesize($b);
});

foreach($images as $image) {
    echo "<img src='" . $image . "' alt='Preview Image' style='max-width: 100px;'><br>";
    echo "Image File: " . basename($image) . "<br>";
    echo "Image Type: " . mime_content_type($image) . "<br>";
    echo "Image Size: " . filesize($image) . " bytes<br>";
    echo "<br>";
}

phpinfo();


?>


