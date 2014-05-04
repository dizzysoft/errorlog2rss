/* Script by David Zimmerman

http://www.dizzysoft.com/

Please feel free to use as long as you give me credit and understand there is no warranty that comes with this script.
*/

$lines= array_reverse(file(""error_log""));
$url= 'http://'.$_SERVER['HTTP_HOST'];
$url.= substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'],'/')+1);

$doc= new DomDocument('1.0');

// create root node
$root = $doc->createElement('rss');
$doc->appendChild($root);
$version = $doc->createAttribute('version');
$root->appendChild($version);
$text= $doc->createTextNode('2.0');
$version->appendChild($text);
$channel= $doc->createElement('channel');
$root->appendChild($channel);

// nodes of channel
$info= $doc->createElement('title');
$channel->appendChild($info);
$text= $doc->createTextNode('Error log for '.$url);
$info->appendChild($text);
$info= $doc->createElement('link');
$channel->appendChild($info);
$text= $doc->createTextNode($url.'error_log');
$info->appendChild($text);
$info= $doc->createElement('description');
$channel->appendChild($info);
$text= $doc->createTextNode(""This is the apache error log for $url"");
$info->appendChild($text);
$info= $doc->createElement('lastBuildDate');
$channel->appendChild($info);
$text= $doc->createTextNode(Date('r')); // now
$info->appendChild($text);

// items for this channel
foreach($lines as $line) {
$item = $doc->createElement('item');
$channel->appendChild($item);

$child = $doc->createElement('title');
$item->appendChild($child);
$first_colon= strpos($line, "": "", 23);
$title= substr($line, 23, $first_colon-23);
$value = $doc->createTextNode($title);
$child->appendChild($value);

$child = $doc->createElement('description');
$item->appendChild($child);
$value = $doc->createTextNode($line);
$child->appendChild($value);

$child = $doc->createElement('pubDate');
$item->appendChild($child);
$date= substr($line, 1, 20);
$value = $doc->createTextNode(date('r', strtotime($date)));
$child->appendChild($value);
}
echo $doc->saveXML();
?>