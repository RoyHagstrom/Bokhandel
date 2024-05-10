<?php
echo "Server IP address: ".$_SERVER['SERVER_ADDR']."<br>";
echo "Server name: ".$_SERVER['SERVER_NAME']."<br>";
echo "Server software: ".$_SERVER['SERVER_SOFTWARE']."<br>";
echo "Server administrator: ".$_SERVER['SERVER_ADMIN']."<br>";
echo "Server port: ".$_SERVER['SERVER_PORT']."<br>";
echo "Server protocol: ".$_SERVER['SERVER_PROTOCOL']."<br>";
echo "Document root: ".$_SERVER['DOCUMENT_ROOT']."<br>";
echo "Remote IP address: ".$_SERVER['REMOTE_ADDR']."<br>";
echo "Remote port: ".$_SERVER['REMOTE_PORT']."<br>";
echo "Request method: ".$_SERVER['REQUEST_METHOD']."<br>";
echo "Request URI: ".$_SERVER['REQUEST_URI']."<br>";
echo "Script filename: ".$_SERVER['SCRIPT_FILENAME']."<br>";
echo "Script name: ".$_SERVER['SCRIPT_NAME']."<br>";
echo "Query string: ".$_SERVER['QUERY_STRING']."<br>";
echo "HTTP Host: ".$_SERVER['HTTP_HOST']."<br>";
echo "HTTP User Agent: ".$_SERVER['HTTP_USER_AGENT']."<br>";
echo "HTTP Accept: ".$_SERVER['HTTP_ACCEPT']."<br>";
echo "HTTP Accept Language: ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."<br>";
echo "HTTP Accept Encoding: ".$_SERVER['HTTP_ACCEPT_ENCODING']."<br>";
echo "HTTP Connection: ".$_SERVER['HTTP_CONNECTION']."<br>";
phpinfo();
?>
