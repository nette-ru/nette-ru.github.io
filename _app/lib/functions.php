<?



// benchmark($func_list, $repeat=1000)
// сравнивает скорость выпонения нескольких функций
// и выводит отчет о выполнении
// пример:  benchmark('a,b,c,d,e');
function benchmark($func_list, $repeat=1000)
{
	// $funcs = explode(',', 'benchmark_empty,'.$func_list);
	$funcs = explode(',', $func_list);
	$time=array();
	$start_all = microtime(true);

	// пропускаем каждую функцию через цикл и измеряем время выполнения
	foreach ($funcs AS $func)
	{
		$func=trim($func);

		// echo "<br/>func = $func<br/>";

		if (!$func OR !function_exists($func)) continue;

		$start = microtime(true)-$start_all;
		for($i=0; $i<$repeat; $i++) $val = $func();
		$end = microtime(true)-$start_all;

		// echo "start=$start<br/>";
		// echo "end=$end<br/>";

		$time[$func] = $end - $start;

		// echo "delta = ".$time[$func]."<br/>";
	}


	echo "Результаты тестирования:<br/><br/>


		<table border=1 cellspacing=0 cellpadding=3 width=220 >";

	//$emty_time = array_shift($time);
	asort($time);
	foreach ($time as $f=>&$t)
	{
		//$t -= $emty_time;
		$t = round(1000*$t,2);

		echo "<tr><td><b>$f</b>&nbsp;&nbsp;<td align=right nowrap>&nbsp;$t ms";

	}
	echo "</table><br/><br/>";
	exit;
}

function benchmark_empty(){}
$str = "1231241262345673245767";
function test_strpos()
{
	global $str;
	return (bool)(strpos($str, "'parent_id' => '".rand(1,126)."',"));
}
function test_strstr()
{
	global $str;
	return (bool)(strpos($str, "'parent_id' => '".rand(1,126)."',"));
}

// benchmark('test_strpos,test_strstr');






// извлекает значние из массива $_GET, без вывода ошибок
function GET($key, $default=null)
{
	return isset($_GET[$key])? $_GET[$key] :$default;
}

// извлекает значние из массива $_POST, без вывода ошибок
function POST($key, $default=null)
{
	return isset($_POST[$key])? $_POST[$key] :$default;
}

// извлекает значние из массива COOKIE $_COOKIE , без вывода ошибок
function COOKIE($key, $default=null)
{
	return isset($_COOKIE[$key])? $_COOKIE[$key] :$default;
}


// val(&$var) - возвращает значение переменной, а если она не установлена - возвращает значение $default или null
function val(&$var, $default=null)
{
	if (isset($var)) return $var;
	else return $default;
}
//test: var_dump( val($_GET)); exit;
/*
$a=array();
echo val($a['d'], 1);
echo val($a['d'], 1);
var_dump($a);
exit;
pr(val($a, 1));
*/

// find_is_set(&$var1, &$var2, ... ) - возвращает первое установленное значение из списка переменных или null, если такое не найдено
function find_is_set(&$var1, &$var2=null, &$var3=null, &$var4=null, &$var5=null, &$var6=null, &$var7=null, &$var8=null, &$var9=null, &$var10=null )
{
    for($i=1; $i<=10; $i++) {


		if (isset(${'var'.$i}) && null!==${'var'.$i} ) return ${'var'.$i};
	}
	return null;
}
//test:
//$d=4;
//pr(var_dump(find_is_set($a,$b,$c,$d)));


/*** find_not_empty($val1, $val2,... )  - возвращает первое непустое значение из списка аргументов; если не найдено, возвращает false.

Чтобы проверять переменные, которые, возможно, несуществуют (неустановлены), их нужно передавать по ссылке. Пример:  $mode = find_not_empty(&$mode, &Core::$Config->mode , &$_GET['mode'], 'mode_default');
***/
function find_not_empty()
{
    $vars = func_get_args();
    foreach ($vars as $v)
	{
		$v=trim($v); if (!empty($v)) return $v;
	}
	return false;
}


function not_empty()
{
    $vars = func_get_args();
    foreach ($vars as $v)
	{
		if (is_string($v)) $v=trim($v);
		if (!empty($v)) return $v;
	}
	return $v;
}



/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @from WP 2.0.5+
 *
 * @param mixed $data Value to check to see if was serialized.
 * @return bool False if not serialized and true if it was.
 */
function is_serialized( $data ) {
	// if it isn't a string, it isn't serialized
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	if ( 'N;' == $data )
		return true;
	if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
		return false;
	switch ( $badions[1] ) {
		case 'a' :
		case 'O' :
		case 's' :
			if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
				return true;
			break;
		case 'b' :
		case 'i' :
		case 'd' :
			if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
				return true;
			break;
	}
	return false;
}


/**
 * Serialize data, if needed.
 *
 * @since 2.0.5
 *
 * @param mixed $data Data that might be serialized.
 * @return mixed A scalar data
 */
function maybe_serialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) )
		return serialize( $data );

	if ( is_serialized( $data ) )
		return serialize( $data );

	return $data;
}


/**
 * Unserialize value only if it was serialized.
 *
 * @since 2.0.0
 *
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
function maybe_unserialize( $original ) {
	if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	return $original;
}


// dump($var, $isHTML=true)	 - выводит (или возвращает) дамп переменной, без использования буферизации вывода
// может работать внутри обрабочика ob_start
// Параметр $isHTML (по умолчанию true) влияет на представление переносов строк ("<BR>" или "\n") и пробелов ("&nbsp;" или " ")
// TODO: http://ru2.php.net/%20print_r - хорошие примеры для определения рекурсс, расцветки и построения дерева
function dump_var($var, $isReturn=false, $level=0)
{
    static $counter=0;
	$hidden_level = 1;

	if($level>6 ) return '... ';
	$isHTML=1;
	$SPACE 	= $isHTML ? "&nbsp;" : " ";
	$SPACE 	= " ";
	$TAB	= str_repeat($SPACE, 3);
	$PREFIX = str_repeat($TAB, $level);
	$NL 	= ($isHTML ? "<BR>" 	 : "\n") .$PREFIX;

	$result = '';

	if (is_array($var) || is_object($var))
	{

		$link_text = 'array';
		if (is_object($var))  {
			$var = (array)$var; $link_text = 'object' ;
			}
		$span_id = 'dump_'.++$counter;

		$count = count($var);

		if ($count)
			$result .= "<a class=dump_dashed href='javascript:dump_toggle(\"{$span_id}_mark\"); dump_toggle(\"$span_id\");' onclick='this.blur();' >$link_text</a>";
		else
			$result .= $link_text;

		$result .= "<sup>".$count."</sup>";


		$style = $level>=$hidden_level && count($var)  ? " style='display:none' " : "";
		$style_mark = $style? "" : " style='display:none' ";

		$result .= "<span id={$span_id}_mark  $style_mark >(...)</span><span id=$span_id  $style >(";

			foreach ($var as $key => $value)
			{
				$result .= 	$NL.$TAB;
				if ($key === 'GLOBALS')
					$result.= '[GLOBALS] => *** Recursive Array ***';
				else
					$result.= "[$key] => "
								.dump($value, 1, $level+1);
				$result .= ',';
			}
			if (count($var))
				$result = rtrim($result, ',') . $NL;
		$result .= ')</span>';
    }
	elseif (is_string($var))
	{
		$len = strlen($var);
		$var = "'".htmlspecialchars($var, ENT_QUOTES)."'";

		if ($len<=1000) $result .= $var;
		else
		{
			$span_id = 'dump_'.++$counter;

			$result .= "<a href='javascript:dump_toggle(\"{$span_id}_mark\"); dump_toggle(\"$span_id\");' onclick='this.blur();'
				class=dump_dashed
				>(string)</a><sup>$len</sup><span id={$span_id}_mark >... </span><span id=$span_id  style='display:none' >$var</span>";

		}

    }
	elseif (is_bool($var))	$result .= $var ? 'TRUE' : 'FALSE';
    elseif (is_null($var))	$result .= 'NULL';
    else $result .= $var;

	if ($isHTML && !$level)
	{
		$result = "
			<style>
			PRE.dump {
				 margin:0;
			}
			pre {
				white-space: pre-wrap;       /* css-3 */
				white-space: -moz-pre-wrap;  /* Mozilla, начиная с 1999 года */
				white-space: -pre-wrap;      /* Opera 4-6 */
				white-space: -o-pre-wrap;    /* Opera 7 */
				word-wrap: break-word;       /* Internet Explorer 5.5+ */
			}
			.dump A {
				color:#2545BE;
				white-space:nowrap;
			}
			.dump A:hover {
				color:red;
				text-decoration:none;
			}

			.dump_dashed {
				border-bottom: 1px dashed #000;
				}
			A.dump_dashed {
				border-bottom: 1px dashed #2545BE;
				text-decoration:none;
				}
			A.dump_dashed:hover {
				border-bottom: 0;
				text-decoration:none;
				}

			.dump SUP {
				color:#999;
				}
			</style>
			<script>
			function dump_toggle(id){
				var s= document.getElementById(id).style;
				s.display= s.display=='none' ? s.display='' : 'none';
			}
			</script>
			<pre class=dump >$result</pre>";
	}
	if ($isReturn) return $result;
	else print $result;
}

// аналог dump() - но по умолчанию возвращает HTML-дамп строкой
function get_dump($var)
{
	return dump($var, $isReturn=true);
}


// заменяет в шаблоне $template все входения %key% на $value (где key - это ключи в массиве $vars, а $value - значения в этом массиве)
function vars_replace($vars, $template)
{
	$keys=array_keys($vars);
	foreach ($keys as &$key)
		$key = '%'.trim($key,'%').'%';

	return str_replace( $keys, $vars, $template);
}

// подгатавливает строку для вывода в составе HTML страницы
function html($str)
{
	return htmlspecialchars($str, ENT_QUOTES);
}

// подгатавливает строку для вывода через JavaScript alert()
function html_alert($str)
{
	return 	str_replaces(strip_tags($str), array(

				"\n"	=> '\n',
				'"'		=> '\\"',
				"'"		=> "\\'",

			));
}


// форматирует русский текст, по правилам типографики
// а тут - пцелый php-класс http://rmcreative.ru/blog/post/tipograf
function typograph($text)
{
    $text = html_entity_decode($text, ENT_QUOTES, 'utf-8');

    // правила замены
    $arr = array(
        // Убираем символ троеточия
        '/…/u' => '...',

        // Кавычки «ёлочки» &laquo; &raquo;
        '/(^|[\s;\(\[-])"/' => '$1«',
        '/"([\s-\.!,:;\?\)\]\n\r]|$)/' => '»$1',
        '/([^\s])"([^\s])/' => '$1»$2',

        // Длинное тире &mdash;
        '/(^|\n|["„«])--?(\s)/u' => '$1—$2',
        '/(\s)--?(\s)/' => ' —$2',

        // Непереносимый проблел после коротких слов &nbsp;
        '/([\s][a-zа-яё]{1,2})[\s]+/iu' => '$1&nbsp;'


// более точный вариант (недоработано)
// '(:?а|без|безо|в|во|вне|да|для|до|ее|ещё|еще|и|или|из|изо|или|их|за|к|как|ко|меж|между|на|над|надо|не|ни|но|о|об|обо|от|ото|перед|передо|по|под|подо|пред|предо|при|про|около|с|сквозь|со|то|там|у|уж|что|через|я|'.'&mdash;')';
//'/(?<=\s|^|\W)'.$prepos.'(\s+)/i' => '$1&nbsp;',

    );

    foreach ($arr as $key=>$val) {
        $text = preg_replace($key, $val, $text);
    }

    // Вложенные кавычки &bdquo; &ldquo;
    while (preg_match('/(«[^«»]*)«/mu', $text)) {
        $text = preg_replace('/(«[^«»]*)«/mu', '$1„', $text);
        $text = preg_replace('/(„[^„“«»]*)»/mu', '$1“', $text);
    }

    return $text;
}




// возвращает URL адрес, соответствующий серверному пути к файлу
function path2url($path)
{
	// вычисляем корневую директорию сайта
	$site_root = $_SERVER['DOCUMENT_ROOT'];

    if(!$site_root)
    {
        $site_root = getenv('DOCUMENT_ROOT');
        trigger_error('Не инициализирована переменная $_SERVER["DOCUMENT_ROOT"] ');
    }

    // учитываем возможность симлиноков в серверном пути
    $site_root2 = realpath($site_root);
    if( $site_root2== $site_root )  $site_root2='';

    $site_root = str_replace('\\','/', $site_root);
    $site_root2 = str_replace('\\','/', $site_root2);

	$path = str_replace('\\','/', $path);


	$url = str_replace(
                array( '^^'.$site_root,   '^^'.$site_root2,   '^^' ),
                '',
                '^^'. ( $path = str_replace('\\','/', $path) )
			);

    $url = '/' . ltrim($url,'/'); // обеспечиваем слеш в начале URL

	return $url;
}

// Превращает URL (или абсолютный путь) - в абсолютный путь
function url2path($url)
{
	// удаляем домен и протокол
	$url = preg_replace('/^(s?f|ht)tps?:\/\/[^\/]+/i', '', trim($url));

	if (file_exists($url)) return $url;

	// вычисляем путь к корню сайта
	$site_root = $_SERVER['DOCUMENT_ROOT'] ;

	// нормализуем строки
	$url = str_replace('\\','/',$url);
	$site_root = str_replace('\\','/',$site_root);

	// если на вход передан абсолютный путь - возращаем его
	if (strstr($url, $site_root))  $path = $url;
	else // иначе добавляем вначале путь к корню сайта
		$path =  $site_root . '/' . ltrim($url,'/') ;

	return $path;
}


// добавляет к URL-адресу файла (css, js) - суффикс, основанный на времени изменения файла (для обновления кеша в браузере)
// $do4local = false   – по умолчанию, работает только на удаленном сервере
function add_time_suffix($file_url, $do4local = false )
{
    $is_local = (substr($_SERVER['REMOTE_ADDR'],0,8) == "192.168.") || ($_SERVER['REMOTE_ADDR'] == "127.0.0.1");

    if ($do4local==false &&  $is_local ) return $file_url;

    $file_url .= '?' . filemtime(url2path($file_url));

    return $file_url;

}



 /*
 * Undo magic quotes
   http://php.net/manual/en/security.magicquotes.disabling.php.
 */
function undo_magic_quotes($arr=null)
{

	ini_set('magic_quotes_sybase', 0);
    ini_set('magic_quotes_runtime', 0);

	// if($arr[email_to] == 'shufflexp@gmail.com') $test=1; else $test=0;


    if (function_exists('get_magic_quotes_gpc')
        && get_magic_quotes_gpc() === 1
        )
    {

//if($test) pr(1,$arr);

		if($arr) return array_map('stripslashes_deep', $arr);

        $_POST    = array_map('stripslashes_deep', $_POST);
        $_GET     = array_map('stripslashes_deep', $_GET);
        $_COOKIE  = array_map('stripslashes_deep', $_COOKIE);
        $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
    }
	else
		return $arr;


  //if($test) pr(2,$arr);
}

function stripslashes_deep($value)
{
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);

    return $value;

    /* код ниже применим, если понадобиться убирать слеши и в ключах массивов (если в ключах - кавычки)
    if (!is_array($value)) return stripslashes($value);

    $result = array();
    foreach ($value as $key => $val)
    {
      $result[ trim(stripslashes($key), '\'\"') ] = stripslashes_deep($val);
    }

    return $result;
    */
}


// return filename extention
function file_ext($filename)
{
	//print_r($filename); echo "| ";
	return strtolower(substr(strrchr(basename($filename), '.'),1));
}
//
// pr(file_ext('.text.txt'));




// return filename without extention
function file_wo_ext($filename)
{
    if (FALSE===strrpos($filename,'.'))
		return $filename;
	else
		return substr($filename,0,strrpos($filename,'.'));
}


function sanitize_filename($fname)
{

	$fname = str_replace(array('\\','/','..',':'),'',$fname);
	return $fname;
}

function sanitize_urlpath($path, $only_local=true)
{
	// удаляем из пути протокол и домен
	if ($only_local)
		$path = preg_replace('/^(s?f|ht)tps?:\/\/[^\/]+/i', '', $path);

	// удалям из пути символы:  ':', '../' ,'<' , '>'
	$path = str_replace(array(':','../','..\\','<','>'),'',$path);

	// стратуем url-путь с '/'
	if ($path && $path{0}!='/') $path = '/' . $path;

	return $path;
}


 /**
 * Convert a string to the file/URL safe "slug" form
 *
 * @param string $string the string to clean
 * @param bool $is_filename TRUE will allow additional filename characters
 * @return string
 */
function sanitize($string = '', $is_filename = FALSE)
{
 // Replace all weird characters with dashes
 $string = preg_replace('/[^\w\-'. ($is_filename ? '~_\.' : ''). ']+/u', '-', $string);

 // Only allow one dash separator at a time (and make string lowercase)
 return mb_strtolower(preg_replace('/--+/u', '-', $string), 'UTF-8');
}


// Зауершает скрипт с HTTP-заголвооком и выводом ошибки
function exit_error($code, $message)
{
	if ($code==400) header('HTTP/1.1 400 Bad Request');
	if ($code==404) header('HTTP/1.1 404 Not Found');
	if ($code==500) header('HTTP/1.1 500 Internal Server Error');

	echo ($message);
	trigger_error($message, E_USER_ERROR );
	exit;
}


 // вычисляет mime-тип опо имени или расширению файла
 function ext2mime($file)
 {
	$mimes = ext_mime_list();
	$ext = file_ext('.' . $file);
	$mime = val($mimes[$ext] , "application/octet-stream"); //"text/plain"
	return $mime;
 }


  // вычисляет  расширение файла, соответсвтующее указанному mime-типу
 function mime2ext($mime)
 {
	$mimes = array_flip( ext_mime_list() );
	$ext = val( $mimes[$mime] );
	return $ext;
 }

 function ext_mime_list()
 {
	return array(
            "pdf"	=> "application/pdf",
            "exe"	=> "application/octet-stream",
            "zip"	=> "application/zip",
            "rar"	=> "application/rar",
            "doc"	=> "application/msword",
            "xls"	=> "application/vnd.ms-excel", //application/x-msexcel
            "ppt"	=> "application/vnd.ms-powerpoint",
            "gif"	=> "image/gif",
            "png"	=> "image/png",
            "jpeg"	=> "image/jpg",
            "jpg"	=> "image/jpg",
            "jpe"	=> "image/jpg",
            "ico"	=> "image/x-icon",
            "wma"	=>	"audio/x-ms-wma",
            "mp3"	=>	"audio/mpeg",
            "wav"	=>	"audio/x-wav",
            "mpeg"	=>	"video/mpeg",
            "mpg"	=>	"video/mpeg",
            "mpe"	=>	"video/mpeg",
            "mov"	=>	"video/quicktime",
            "avi"	=>	"video/x-msvideo",
            "wmv"	=>	"video/x-ms-wmv",
            "swf"	=>	"application/x-shockwave-flash",
            "htm"	=>	"text/html", // 'text/html; charset=utf-8'
            "html"	=>	"text/html",
            "txt"	=>	"text/plain",
            "log"	=>	"text/plain",
            "css"	=>	"text/css",
            "js"	=>	"application/x-javascript",
            "rss"	=>	"text/xml",
            "xml"	=>	"text/xml",

            //"default"	=> "application/octet-stream"
            //"default"	=> "application/force-download",  // application/download"); //application/x-download
		);
 }


// передает в бразуер содержимое файла с ссответствующими HTTP-заголовками
// статичные файлы просто выводит, а php-скрипты исполняет через include
function file_echo($file)
{
	// pr($file);

	if(!file_exists($file))
	{
		header("HTTP/1.1 404 Not Found");
		return;
	}

	$file_ext = file_ext($file);
	// pr($file_ext);

	if ($file_ext=='php')

	{
		include($file);
		return;

	}

	// вычисляем подходящий Content-Type
	$ctype = array_search($file_ext,
            array_flip(array(
            "pdf"	=> "application/pdf",
            "exe"	=> "application/octet-stream",
            "zip"	=> "application/zip",
            "rar"	=> "application/rar",
            "doc"	=> "application/msword",
            "xls"	=> "application/vnd.ms-excel", //application/x-msexcel
            "ppt"	=> "application/vnd.ms-powerpoint",
            "gif"	=> "image/gif",
            "png"	=> "image/png",
            "jpeg"	=> "image/jpg",
            "jpg"	=> "image/jpg",
            "jpe"	=> "image/jpg",
            "ico"	=> "image/x-icon",

            "wma"	=>	"audio/x-ms-wma",
            "mp3"	=>	"audio/mpeg",
            "wav"	=>	"audio/x-wav",
            "mpeg"	=>	"video/mpeg",
            "mpg"	=>	"video/mpeg",
            "mpe"	=>	"video/mpeg",
            "mov"	=>	"video/quicktime",
            "avi"	=>	"video/x-msvideo",
            "wmv"	=>	"video/x-ms-wmv",
            "swf"	=>	"application/x-shockwave-flash",

            "htm"	=>	"text/html", //text/html; charset=utf-8'
            "html"	=>	"text/html",
            "txt"	=>	"text/plain",
            "log"	=>	"text/plain",
            "css"	=>	"text/css",
            "js"	=>	"application/x-javascript",
            "rss"	=>	"text/xml",
            "xml"	=>	"text/xml",

            //"default"	=> "application/octet-stream"
            //"default"	=> "application/force-download",  // application/download"); //application/x-download


		)));

    if(FALSE===$ctype) $ctype='';

		header("Content-Type: $ctype");

		header("Content-Length: ". (string)(@filesize($file)));



    readfile($file);
    // exit;


}

// возаращает ассоциативный массив всех HTTP-заголовков , подготовленных или уже отправленных
function headers_array($k=null)
{
	$headers = array();
	$list = headers_list();

	foreach ($list as $header)
	{
		list($key,$val) = explode(':', $header);
		$key = trim($key); $val = trim($val);
		$headers[$key] = $val;
	}
	if($k) $headers = val($headers[$k]);
	return $headers;
}


 /**
 * Get the directory size
 * @param directory $directory
 * @return integer
 */
function dir_size($directory) {
    $size = 0;
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
        $size+=$file->getSize();
    }
    return $size;
}

// pr(memory_get_usage());


// конвертирует результат ini_get() (4K , 5M , 10G) - в байты
function return_ini_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

function format_ini_bytes($bytes)
{
	$result = ceil($bytes/1048576).'M';
	return $result;
}

/**
 * Converts human readable file size (e.g. 10 MB, 200.20 GB) into bytes.
 *
 * @param string $str
 * @return int the result is in bytes
 * @author Svetoslav Marinov
 * @author http://slavi.biz
 */
function filesize2bytes($str) {
    $bytes = 0;

    $bytes_array = array(
        'B' => 1,
        'KB' => 1024,
        'MB' => 1024 * 1024,
        'GB' => 1024 * 1024 * 1024,
        'TB' => 1024 * 1024 * 1024 * 1024,
        'PB' => 1024 * 1024 * 1024 * 1024 * 1024,
    );

    $bytes = floatval($str);

    if (preg_match('#([KMGTP]?B)$#si', $str, $matches) && !empty($bytes_array[$matches[1]])) {
        $bytes *= $bytes_array[$matches[1]];
    }

    $bytes = intval(round($bytes, 2));

    return $bytes;
}


// форматирует чсило байт - в читабельный краткий формат (100Кб, 2Мб, и т.п. )
function format_bytes($bytes, $precision = 2)
{
    $units = array('б', 'Kб', 'Mб', 'Гб', 'Tб');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . '' . $units[$pow];
}

// форматирует чсило байт - в читабельный краткий формат (100Кб, 2Мб, и т.п. )
function format_price($price, $decimals = 0)
{
    $price = number_format ( $price, $decimals, $dec_point = '.' , $thousands_sep = ' ' );

    $price = str_replace(' ', '&nbsp;', $price);

    return $price;
}

/*
get_number_word() - склоняет существительное после числа.

Пример: get_number_word($count, 'пользователь', 'пользователя'
'пользователей')

*/
function get_number_word($count, $str1, $str2, $str3)
{
	$count = $count % 100;
	if ($count > 9 && $count < 20) return $str3;

	$count = $count % 10;
	if ($count == 1)	return $str1;
	if ($count > 1 && $count < 5) return $str2;

	return $str3;
}

function is_image($fn)
{
	return in_array(file_ext($fn), array('jpg', 'jpeg', 'png', 'gif'));
}


 function url_file_exists($url)
 {
    return file_exists( $_SERVER['DOCUMENT_ROOT'] . $url );
 }



/**
 *  save_array($filename, $data_array) - записывает содержимое многомерного массива в файл в виде php кода
 */
function save_array($filename, $data_array)
{
    file_put_contents( $filename.'~',
        //"\xEF\xBB\xBF". // Записываем в начале 3 байта (utf-8 BOM - byte order mark) для указания кодировки контента как UTF-8, без этого документ распознается в редакторах как ANSI // отключено, так как вызывало проблемы с чтением файлов через PHP5.3 на Маке - руские буквы превращались в знаки вопроса.
		"<? return \n". var_export($data_array, true).";\n?>"
        );
    @unlink($filename);
    rename($filename.'~', $filename);
    chmod($filename, 0666);
    // очищаем кеш для измененного файла (без чтения файла)
    read_array($filename,$clearcache=true);

}


/**
 *  read_array($filename, $clearcache=false) - читаем массив из файла
 *      Если установлен $clearcache - то просто очищает кеш для файла
 */
function read_array($filename, $clearcache=false)
{
    static $cache = array();
    if ($clearcache)
    {
        if($filename) unset($cache[$filename]);
        else $cache=array();
        return;
    }
    if (!empty($cache[$filename])) return $cache[$filename];



    if (file_exists($filename))
	{
		ob_start(); // защищаемся от вывода в браузер "UTF-8 BOM" байтов
		$cache[$filename] = (array) include($filename);
		ob_end_clean();

//        if(strstr($filename,'site.php')) pr($cache[$filename]);

		return $cache[$filename];
    }
	else
        return array();

}




/** rglob($pattern='*', $flags = 0, $path='') - recursive glob()
 * @param int $pattern
 *  the pattern passed to glob()
 * @param int $flags
 *  the flags passed to glob()
 * @param string $path
 *  the path to scan
 * @return mixed
 *  an array of files in the given path matching the pattern.

	Example usage:
	chdir('../');
	var_export(rglob('*.php'));

*/

function rglob($pattern='*', $flags = 0, $path='')
{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}






// возвращает URL адрес, соответствующий серверному пути к директории
// $path - это серверный путь к директории или к файлу в этой директории
function dir_url($path)
{
	return path2url(dirname($path));
}



// возрващает полный url текущей страницы
function current_url()
{return url_current();}

function url_current()
{
	return 	'http'
			.( (empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]!='off')  ? 's' :'')
			.'://'
			.$_SERVER["HTTP_HOST"]
			//.$_SERVER["SERVER_NAME"]
			//.($_SERVER["SERVER_PORT"] != "80"  ?  ":".$_SERVER["SERVER_PORT"] : '' )
			// SERVER_NAME:SERVER_PORT на хостинге Зенон используется при размещении сайта на субдомене, и поэтому не соответствует текущему HTTP_HOST
			.$_SERVER["REQUEST_URI"]
			;
}

// заменяет в URL значения указанных переменных на новые
function url_merge($query_vars, $url=null)
{
	if (!$url) $url = $_SERVER['REQUEST_URI'];
	if (!strstr($url,'?') && strstr($url,'&')) $url = '?'.$url;
	list($path, $query) = explode('?', $url.'?');

	parse_str($query, $query);
	if (!is_array($query_vars)) parse_str($query_vars, $query_vars);
	// pr($query, $query_vars);

	$query = array_merge($query, $query_vars);
	// pr($query);

	$query_new = '';
		foreach ($query as $var => $val)
			if($val)
				$query_new .= "&$var=$val";

	$url_new = 	$path;
	if ($query_new) $url_new.= '?'.trim($query_new, '&');

	return $url_new;
}
// pr(url_merge(null, 'subfolder=folder&pg='));


// заменяет в URL значения указанных переменных на новые или добавляет в конец, возвращает новый URL
function url_modify($url='', $query_vars='' )
{
	// если URL не передан - берем URL текущей страницы
	if (!$url) $url = $_SERVER['REQUEST_URI'];
	if (!strstr($url,'?') && strstr($url,'&')) $url = '?'.$url;
	list($path, $query) = explode('?', $url.'?');

	parse_str($query, $query);
	if (!is_array($query_vars)) parse_str($query_vars, $query_vars);
	// pr($query, $query_vars);

	$query = array_merge($query, $query_vars);
	// pr($query);

	$query_new = '';
		foreach ($query as $var => $val)
			if($val)
				$query_new .= "&$var=$val";

	$url_new = 	$path;
	if ($query_new) $url_new.= '?'.trim($query_new, '&');

	return $url_new;

}
// pr(url_modify('http://forkom.ru/search.php?in=catalog&text=blog', 'in=&pg=54'));




// рекурсивное копирование директории - вместе со всеми поддиректориями и файлами
function copy_dir($src,$dst)
{
    $dir = opendir($src);
    @mkdir($dst);

    while(false !== ( $file = readdir($dir)))
	{
        if (( $file != '.' ) && ( $file != '..' ))
		{
            if ( is_dir( $src .'/'. $file))
                copy_dir($src .'/'. $file,$dst .'/'. $file);
            else
                copy( $src .'/'.$file, $dst.'/'. $file);
        }
    }
    closedir($dir);
}




//array of files without directories... optionally filtered by extension
function file_list($dir, $filter='', $wo_ext=false, $show_hidden=false, $prefix='')
{
    $list=array();
	if (empty($dir) || !is_dir($dir)) return $list; // TODO: ?? trigger_error ??

	foreach(array_diff(scandir($dir),array('.','..')) as $file)
	{
        if ($file[0]=='.' && !$show_hidden ) continue;
		if ($filter && !strstr($file, $filter)) continue;  //(($x)?ereg($x.'$',$file):1))
		if(!is_file($dir.'/'.$file)) continue;
        if ($wo_ext) $file = file_wo_ext($file);
		if ($prefix) $file = $prefix . $file;
		$list[]=$file;
    }
	sort($list, SORT_REGULAR);
	return $list;
}
//pr(file_list(FILES_DIR)); //$dir, $filter='', $show_hidden=false)


//array of directories (recursive = and subdirectories)
function dir_list($dir, $recursive=false, $show_hidden=false, $prefix='')
{
	static $cache=array();
	$hash= md5(serialize(func_get_args()));
	if(isset($cache[$hash])) return $cache[$hash];

	$l = array();

    $prefix=rtrim($prefix,'/ ');

	if (is_dir($dir))
	foreach(array_diff(scandir($dir),array('.','..')) as $folder)
	{
        if(is_dir($dir.'/'.$folder) )
		{
            if ($folder[0]=='.' && !$show_hidden) continue;

			$l[]=($prefix? "$prefix/" : "") . $folder;
			if ($recursive
				&& $sublist=dir_list(
						"$dir/$folder",
						true, $show_hidden,
						($prefix? "$prefix/$folder" : $folder)
						)
				)
				{

					$l= array_merge($l,$sublist);
				}
		}
    }
	return ($cache[$hash] = $l);
}

//pr( dir_list(FILES_DIR, $recursive=true));


// pr(getimagesize('D:\WebServer\home\sitecomfort\www\sites\forkom\design\img\button_hover.gif'));




/**
 * find_file_exists($file1, file2,... ) - ищет и возращает первый существующий файл. Проверят также пути, заданные от корня сайта (DOCUMENT_ROOT).
 * Если ПЕРВЫЙ аргумент = true , то возращаемый путь раскрывается в полный-абсолютный путь
 *
 * @param  string/array $file1, file2,...  -  полные пути к файлам, любое число аргументов / либо массив / либо массивы со списком файлов
 *
 * @return string - возвращает первый найденный существующий файл, либо false
 */
function find_file_exists()
{
	// извлекаем список путей для проверки
	$files= func_get_args();
	// pr($files);

	// инициализируем режим "разворачивания" путей
	$expand_path = ($files[0]===true);
	if ($expand_path) array_shift($files);

	// инициализируем списко диркторий, где будем искать
	$SITE_ROOT = $_SERVER['DOCUMENT_ROOT']; // корень сайта на сервере
	$SYSTEM_ROOT = SYSTEM_ROOT; // место ррасположение системы(фреймворка) на сервере

	if ($SYSTEM_ROOT==$SITE_ROOT) $SYSTEM_ROOT=false; // если пути одинковы, то экономим операции файловых проверок

	// проверяем каждый файл в цикле

    // for ($i=0,$count=count($files); $i<$count; $i++ ) // ??? может так лучше для рекурсивного вызова по сравнению с foreach ???
  unset($file);
	foreach ($files as $file)
	{
        // $file = $files[$i];
		// echo "$file<br>";

		// если аргумент - массив, то проверяем каждый элемент массива как отдельный файл-путь
    if (is_array($file))
		{
			if ($result = call_user_func_array(__FUNCTION__, $file))
				return $result;
			else
				continue;
		}

		// echo "$file<br>";
    // pr( file_exists('W:/home/sitecomfort_pavlinart/www/sites/forkom/design/img/watermark.png') ? 1111 : 1 );


		// пропуускаем пробельные и пустые строки
		if (!($file=trim($file))) continue;

    // нормализуем разделители директорий
		$file      = str_replace('\\','/',$file);
		$SITE_ROOT = str_replace('\\','/',$SITE_ROOT);
		// echo "$file<br>";

		// кешируем ранее проверенные пути
		static $CHECKED_FILES = array();
		if (isset($CHECKED_FILES[$file]))
		{
			if ($CHECKED_FILES[$file])
				return $CHECKED_FILES[$file];
			else
				continue;
		}

		// пропускаем директории
		if (File::in_open_basedir($file) && is_dir($file))
			{ $CHECKED_FILES[$file] = false; continue;}

		// echo "$file<br>";
		// pr(array($file, $SITE_ROOT, $SYSTEM_ROOT) ,  (strstr($file, $SITE_ROOT) OR strstr($file, $SYSTEM_ROOT)) );


		// проверяем абсолютный путь внутри папки сайта или системы
		if(strstr($file, $SITE_ROOT) OR strstr($file, $SYSTEM_ROOT))
			if ( file_exists($f = $file)
            OR ($f = find_file_nocase($file))
          )
				return  ($CHECKED_FILES[$file] = $f);

		// echo "$file<br>";

		// возможно, это не абсолютный путь, а URL-путь от корня сайта
		if($file{0}!='/') { $CHECKED_FILES[$file] = false; continue;}

		// echo "$file<br>";

		if ( 	(File::in_open_basedir($file_test=$SITE_ROOT.$file) &&
				file_exists($file_test) )
			OR
				( $SYSTEM_ROOT && File::in_open_basedir($file_test=$SYSTEM_ROOT.$file) && file_exists($file_test) )
			)
			{
				$file = $expand_path ? $file_test : $file;
				return ($CHECKED_FILES[$file] = $file);
			}

		/*  // поиск файлов в другом регистре - отключил из-за большой нагрузки и малой необходимости. но может в будущем поонадобиться, опционально
			if ( 	($f = find_file_nocase($SITE_ROOT.$file))
			 OR	($f = find_file_nocase($SYSTEM_ROOT.$file))

			) return str_replace(array($SITE_ROOT,$SYSTEM_ROOT),'', $file);
		 */

	}
	// exit;

	// pr($result);
	if(is_string($file)) $CHECKED_FILES[$file] = false;
    return false;
}


// проверяет, существует ли файл, независмо от регистра в имен файла
// возвращает корректное имя файла.
function find_file_nocase($path)
{
    // static $checked=array();
	// if (isset($checked[$path])) return $checked[$path];
	// echo $path."<br/>";
	// if (file_exists($path)) return $path; else return false;


	$dirname = dirname($path);
    $filename = strtolower(basename($path));

	if(!is_dir($dirname)) return false;


	// проверяем стандартные варианты имени файлов
	$path_variants = array(
		$path,
		$dirname .'/'.ucfirst($filename),
		$dirname .'/'.strtolower($filename),
		// $dirname .'/'.strtoupper($filename),
		);

	foreach ($path_variants as $path_var)
		if (file_exists($path_var ))
		{
			$checked[$path]=$path_var;
			return $path_var;
		}

	/*
	// если не найдено, сканируем весь каталог
    $dir = dir($dirname);
    while ( ($file = $dir->read()) !== false) {
        if (strtolower($file) == strtolower($filename)) {
            $dir->close();
            return  $dirname .'/'.$file;
        }
    }
    $dir->close();
	*/

	// $checked[$path]=false;
    return false;
}

// создает папку и если не сущесвтуют над-папки - то и их тоже
function mkdir_recursive($path, $perms=0777)
{
	$path = str_replace('/', DIRECTORY_SEPARATOR , $path);
	// pr($path);
	mkdir($path, $perms, true);
}







// ищет в $haystack самое раннее вхождение любой из строк переданных в массиве $needles, начиная с позиции $offset
function multi_strpos($haystack ,$needles=array(),$offset=0)
{
    $res = array();
    foreach($needles as $needle){
        if (strpos($haystack,$needle,$offset) !== false) {
          $res[] = strpos($haystack,$needle,$offset);
       }
    }
    if(empty($res)) return false;
    return min($res);
}







function redirect301($url)
{
	redirect($url,0,301);
}

//pr($_SERVER);

/*
redirect($url,$time = 0, $status=302)
	осуществляет редирект с помощью PHP или HTML/JS (если заголвоки уже отправлены или нужна задержка)
Параметры:
	$url - URL с хостом или без
	$time - время задержки в секундах или флаг возврата. Если $time < 0, то делается попытка вернуться в предыдущей странице ($_SERVER['HTTP_REFERER'])
	$status - HTTP-код отклика сервера (301,302, 307, и т.п.)

	Виды редиректа:
	301 Moved Permanently - Перемещено окончательно.
	302 Found (Найдено) - (Temporary redirect - «временно перемещен»)

	http://ru.wikipedia.org/wiki/Список_кодов_состояния_HTTP
*/
function redirect($url='',$time = 0, $status=302)
{
	// время преобразуем к целым секундам
	//$time = intval($time);
	//pr($url);

	//if ($time < 0 && $rurl=Core::get_return_url()) $url=$rurl;

	// URL приводим к безопасному и пригодному виду
	if(!$url) $url=$_SERVER['REQUEST_URI'];

	if($url[0]=='/') $url = 'http://'.$_SERVER['HTTP_HOST'].$url;

	$parsed_url = parse_url($url);
	if (!stristr($url,$_SERVER['HTTP_HOST']) && !isset($parsed_url['host']))
		$url = $_SERVER['HTTP_HOST'] .'/'.  ltrim($url,'/');

	if (!isset($parsed_url['scheme']))
		$url = 'http://' . $url;



	/* FIX:  это некорректно работает с URL вида
			/admin/files/?filter_name=&subfolder=baners%2Ftest
			(%2F - в параметрах)

	$url = urlencode($url);
	$url = str_replace(
		array('%3F','%3D','%26','%2F','%3A','','',''),
		array('?','=','&','/',':','','','',''),
		$url);
	*/
	// pr($time);
	// pr((int)headers_sent());
	//pr($url);

	// если заголовки ещё не отправлены - делаем HTTP-редирект
	if (!headers_sent() && !$time)
	{    //If headers not sent yet... and noneed timeout then do php redirect
        header($str='Location: '.$url, true,  $status);
		//pr($str);
    }
	else // если HTTP-заголовки уже отправлены - делаем редирект с помощью HTML или JavaScript
	{
        echo '
		<script type="text/javascript">
		setTimeout(\'location.replace("'.$url.'")\', '.$time.'000);
		</script>
        <noscript>
		<meta http-equiv="refresh" content="'.$time.';url='.$url.'" />
		</noscript>
		';


    }

	// если задержка не нужна, то завешаем выполнение скрипта
	if (!$time) exit;
}



function redirect_by_routes($routes)
{

}



// pr(current_url());
function pr_test()
{
	$args = func_get_args();
	if (GET('test'))
		call_user_func_array('pr',  $args);
}


// pr() - выводит дампа переменных с остановом
function pr()
{
	$args=func_get_args();

	if ($args && $args[0]!==null )
		while (ob_get_level()) {ob_end_clean();}

	call_user_func_array('pr_',$args);
	exit;
}

// pr() - выводит дампа переменных без останова
function pr_()
{
	header("Content-type: text/html");

	// получаем информацию о вызывающей строке
	$info_all = debug_backtrace();
	foreach ($info_all as $info)
		if (isset($info['file'])
			&& $info['function']!='call_user_func_array'
			) break;
	// echo"<pre>";print_r($info);

	// выводим всю информацию о вызывающей строке
	echo 	'<TABLE ><TR>
			<TD style="background:#F8FA8E; padding:3px 6px; height:25px;"  >
			<b2>Файл</b>: "<b title="'.$info['file'].'">'
			. basename($info['file'])
			.'</b>"'
			.', <b2>строка</b> <b>'.$info['line'] .'</b> '
			.', время скрипта: <b>'.script_time() .' </b> '
			.'<TR><TD style="background:#eee; padding:5px; font: normal  17px monospace;"><b>'
			.$info['line'].':</b> '
			.( ($f=file($info['file']))? html($f[$info['line']-1]) : '')

			.'</TABLE>'
			;

   // обеспечиваем кроссбраузерный перенос длинных строк в теге <PRE>
   print "<style>
	pre {
		white-space: pre-wrap;       /* css-3 */
		white-space: -moz-pre-wrap;  /* Mozilla, начиная с 1999 года */
		white-space: -pre-wrap;      /* Opera 4-6 */
		white-space: -o-pre-wrap;    /* Opera 7 */
		word-wrap: break-word;       /* Internet Explorer 5.5+ */
	}
	</style>
    ";

   // выводим все аргументы функции
   $args=func_get_args();
   foreach ($args AS $var)
   {
        if($var==null) continue;
        print "<pre>\n"
              .htmlspecialchars(@print_r($var, 1))
              ."\n</pre>";

   }

}



// возвращает интервал в милисекундах от последнего вызова script_time(true) (если таковой был) или от самого первого вызова script_time()
script_time(true);
function script_time($is_reset=false)
{
	static $START_TIME=0;

	$this_time = microtime(true);
	$interval =  $START_TIME? round(1000*($this_time-$START_TIME),2) : 0;

	if(!$START_TIME OR $is_reset) $START_TIME = $this_time;
	return $interval.'ms';
}





// extract 1st <img> src from  content with image
function extract_image($content)
{
    if (!stristr($content,'<img')) return '';

    if (preg_match('/<img [^>]*src\s*=\s*["\']?([^"\' ]+)["\' >]/is',$content, $matches))
        return $matches[1];

}

// extract all <img> src from  content to array
function extract_images($content)
{
    if (!stristr($content,'<img')) return '';

    if (preg_match_all('/<img [^>]*src\s*=\s*["\']?([^"\' ]+)["\' >]/is',$content, $matches))
    {
		//pr($matches);

		return $matches[1];
    }


}


// extract_anons($content, $minlen=null, $maxlen=null, $allow_tags='')
//      Extract anons from $content, strip tags and cut at <hr> or at length params
// @param $minlen - min length of anons by chars
// @param $maxlen - max length of anons by chars
//      It make end of anons at the end of sentenses or word
// @$allow_tags - allowed tag in anons, example: '<a><b><p>'
// $param $modde - default 'before' - return anons, 'after' - return string after anons, 'both' - return both string in array
function extract_anons($content, $minlen=null, $maxlen=null, $allow_tags='',$mode='before')
{
    if (!$allow_tags) $allow_tags = '<sup>';

    $content_full = $content;

	if(!$maxlen) $maxlen=350;
    if(!$minlen) $minlen=(int)($maxlen/2);


    if ($hr = strpos($content,'<hr'))
    {

        $content1= strip_tags(substr($content_full, 0,$hr),$allow_tags);
        $content2= strip_tags(substr($content_full,$hr),$allow_tags);

    }
    else
    {

        // cut content according $maxlen param
        $content= $content_full = strip_tags($content,$allow_tags);

        $content= mb_substr(str_replace('&nbsp;',' ',$content),0,$maxlen+1,'utf-8');




		// find last sentences delimiter OR last word delimiter
		if( preg_match('/([\.\?\!])/', strrev($content), $match1, PREG_OFFSET_CAPTURE )
			|| preg_match('/([\s,:;])/', strrev($content), $match2, PREG_OFFSET_CAPTURE ))
        {
            $last_delim1 = strlen($content)-@$match1[0][1];
            $last_delim2 = strlen($content)-@$match2[0][1]-1;

			$last_delim = strlen($content);
			if ($last_delim1>=$minlen)  $last_delim=$last_delim1;
			else if ($last_delim2>=$minlen)  $last_delim=$last_delim2;


            // extract string from start to last delimiter
            $content1= substr($content,0,$last_delim);
            $content2= trim(substr($content_full,$last_delim));
        }
        else {$content1=$content2=$content;}

        // FIX ME: its need to close non closed HTML tags

        //pr("'$content'");
    }

    $content1 = trim($content1);
    $content2 = trim($content2);

    if (!$mode || $mode=='before') return $content1;
    if ($mode=='after') return $content2;
    if ($mode=='both')  return array($content1,$content2);

}






// обрезает строку и добавляет троеточие
function str_limit($str, $maxlength = 256, $continue = "\xe2\x80\xa6", &$is_cutted = false)
{
    // pr(mb_internal_encoding());
	// mb_internal_encoding(cfg()->CHARSET);
	mb_internal_encoding('UTF-8');

	$str = strip_tags($str);
	if (mb_strlen($str) <= $maxlength) return $str;
	$str=mb_substr($str,0,$maxlength) . $continue;
    $is_cutted = true;
    return $str;
}
//pr(str_limit('123456789012345', 10));
// pr(str_limit('абвгabcdefghiklmnop', 10));
// pr(str_limit('абвгдеёжзиклмн', 10));



/**
 * short_text($content, $size=200, $allow_tags='') - создает краткий анонс из текста статьи.  Пытается выделить в анонс текст, завершаемый разделителем предложений (!?.) или разделителем слов (пробел,;:)
 *
 *
 */

function short_text($content, $size=200, $allow_tags='')
{
    // pr($size, $allow_tags, $content);

	//$allowed_tags = "<p><b><i><u><ul><ol><span><a><br><div><font><br/><table>";
	$allow_tags .= '<sup>'; // в анонсе сохраняем некоторое форматирование

	// устанавливаем примелимый предел для поиска разделителей слов и предложений
	$maxlen = $size+25;
	$minlen= max(50, $size-50);

	// подготавливаем максимальный по размеру анонс, без учета разделителей слов

	// обеспечиваем пробелы в местах переноса строк
	$content = str_replace(
		array('&nbsp;'	, '<br'	,'<BR'	,'</p>'	,'</P>'),
		array(' '		, ' <br',' <BR'	,' </p>',' </P> '),
		$content
		);
	$content = strip_tags($content,$allow_tags);
	// pr($content);
	// pr(mb_substr($content, 0, $maxlen+1));

	mb_internal_encoding("UTF-8");
	$content = mb_trim(mb_substr($content, 0, $maxlen+1));
    // pr($size, $content );


	if(empty($content)) return $content;
    // if(strlen($content)<$minlen) return $content;

	// Теперь ищем подходящий разделитель предложений
    $end = max(	mb_strrpos( $content , '!')+1,
				mb_strrpos( $content , '?')+1,
				mb_strrpos( $content , '. ')+1
				);
    // pr($end);

	// если не найден (в приемлимом диапазоне), ищем раделитель слов
	if ($end < $minlen)
	$end = max(	mb_strrpos( $content , ' ')
				,mb_strrpos( $content , ':')
				,mb_strrpos( $content , ';')
				,mb_strrpos( $content , ',')
				,mb_strrpos( $content , "\n")

				);
	// pr($content,$end);

	// если не найден - принудительно обрежем контент по заданному size
	if ($end < $minlen)
		$end = $size;

	//pr($end);

	$content = mb_substr($content,0,$end);

	$content =  close_tags($content, $allow_tags);

    //pr("'$content'");

	return $content;
}


// 	расставляет переносы в русскоязычной строке текста в UTF-8
//  http://quittance.ru/blog/index.php?category=6
function hyphenate($text)
{
	static $Hy=null;
	if (!$Hy) $Hy = new phpHypher(); // подключаем библиотеку для расстановки переносов в русском тексте (/system/libs/phphypher/)
	// pr($Hy);

	$text = $Hy->hyphenate($text, 'UTF-8');

	return $text;
}



//  реализация алгоритма расстановки переносов адаптированная к windows-1251
function hyphen_words($text)
{
    #буква (letter)
    $l = '(?:[ёЁА-я]  #ё Ё А-я (все)
           | [a-zA-Z]
           )';

    #гласная (vowel)
    $v = '(?:[аеиоуыэюяё]  #аеиоуыэюяё (гласные)
           | [АЕ�?ОУЫЭЮЯЁ]         #АЕ�?ОУЫЭЮЯЁ (гласные)
           | (?i:[aeiouy])
           )';

    #согласная (consonant)
    $c = '(?:[бвгджзклмнпрстфхцчшщ]  #бвгджзклмнпрстфхцчшщ (согласные)
           | [БВГДЖЗКЛМНПРСТФХЦЧШЩ]                #БВГДЖЗКЛМНПРСТФХЦЧШЩ (согласные)
           | (?i:sh|ch|qu|[bcdfghjklmnpqrstvwxz])
           )';

    #специальные
    $x = '(?:[ЙЪЬйъь])';   #ЙЪЬйъь (специальные)

    /*
    #алгоpитм П.Хpистова в модификации Дымченко и Ваpсанофьева
    $rules = array(
        # $1       $2
        "/($x)     ($l$l)/sx",
        "/($v)     ($v$l)/sx",
        "/($v$c)   ($c$v)/sx",
        "/($c$v)   ($c$v)/sx",
        "/($v$c)   ($c$c$v)/sx",
        "/($v$c$c) ($c$c$v)/sx"
    );
    */

    #improved rules by D. Koteroff
    $rules = array(
        # $1       $2
        "/($x)     ($l$l)/sx",
        "/($v$c$c) ($c$c$v)/sx",
        "/($v$c$c) ($c$v)/sx",
        "/($v$c)   ($c$c$v)/sx",
        "/($c$v)   ($c$v)/sx",
        "/($v$c)   ($c$v)/sx",
        "/($c$v)   ($v$l)/sx",
    );

    #\xad = &shy;
    $text = preg_replace($rules, "$1\xad$2", $text); // не \xc2\xad
    return  $text;
}




/**
 *  close_tags($code, $tags) - добавляет закрывающие теги, если в коде есть незакрытые теги из указанного списка.
 *  Пример:
		$content =  close_tags($content, '<a><b><li><font>')
		$content =  close_tags($content, 'a b li font')
 */
function close_tags($code, $tags)
{
	$tags = str_replace(array('<', '>', ',', '    ','  '),' ', $tags);
	$tags = explode(' ',$tags);

	$code_lower = strtolower($code);
	foreach ($tags as $tag)
	{
		if (!trim($tag)) continue;
		$tag = strtolower($tag);


		// TODO: spliti => explode / preg_split
		// $opened_count = count(spliti('<'.$tag,  $code));
		// $closed_count = count(spliti('</'.$tag, $code));

		$opened_count = count(explode('<'.$tag,  $code_lower));
		$closed_count = count(explode('</'.$tag, $code_lower));


		if ($opened_count > $closed_count)
			$code .= str_repeat( "</$tag>", $opened_count - $closed_count );
	}

  return $code;
 }






/***
eval_phtml($phtml, $vars_array=array())
    - интерпретиирует код HTML+PHP и возвращает код HTML

@param $phtml   - html код , который моежт содержать вставки php кода.
@param $vars_array - ассоциативный массив, занчение которого импортируются как переменные в область видимости кода $phtml. Если элементы массива передаются как ссылки, то код в $phtml может их изменять.

Пример 1:
    $template= '<h1><?=$h1?></h1> <?if($text){?> <p><?=$text?></p> <?}?>';
    $h1      = 'Какой-то заголовок';
    $text    = 'Какой-то текст';
    print $content = eval_phtml($template, get_defined_vars());

    // вывод:
    // <h1>Какой-то заголовок</h1> <p>Какой-то текст</p>

Пример 2:
    $content = eval_phtml($template, array('h1'=>'Заголовок', 'text'=>'текст'));

    // вывод:
    // <h1>Заголовок</h1> <p>текст</p>

Пример 3:
    (использование передачи переменных-ссылок для возможности записи в переменные)

    $x=1;
    echo "старое значение: \$x=$x  <br>";

    $phtml='<? $x=2; ?>';
    eval_phtml($phtml, array(x=>&$x));
    echo "новое значение: \$x=$x  <br>";

    // вывод:
    // старое значение: $x=1  <br>
    // новое значение: $x=2   <br>

*/
function eval_phtml($eval_phtml_code, $arguments=array())
{
	if(FALSE === strpos($eval_phtml_code,'<?')) return $eval_phtml_code;

	if (is_object($arguments)) $arguments = (array)$arguments;
    if ($arguments && is_array($arguments))
    {
        extract($arguments, EXTR_REFS);
        //if($element) extract($element);
    }
    ob_start();
	//
	@$eval_phtml_success=eval("?>$eval_phtml_code<? return 1;");
	$eval_phtml_result=ob_get_contents();
	ob_end_clean();

    // отлавливаем ошибки в коде
    //pr($eval_phtml_success);

    if (!$eval_phtml_success && defined('DEBUG') && DEBUG)
    {
        // если нужно, здесь переводим сообщения об ошибках
        // $eval_phtml_result=error_translate($result);

        echo  $eval_phtml_result; // выводим сообщение об ошибке
        print_code($eval_phtml_code); // выводим  нумерованый код
        exit;
    }
    return $eval_phtml_result;
}

// рекурсивно обрабатывает элементы массива, производя в них замену строк и интерпретацию php кода
function  array_replace_eval( &$arr, $replace_list, $vars=array())
{
	//echo "$arr->id | ";
	//if($arr->id=='blog') pr($arr);

	/* $code = serialize($arr);
	if (false===strpos($code,'<?')
		&& false===strpos($code,'%')
		) return;  */

	foreach ($arr as &$item)
	{
		if (is_array($item))
			array_replace_eval( $item, $replace_list, $vars);
		else
		{
			$item = str_replace ( array_keys($replace_list) , $replace_list, $item);
			if (false!==strpos($item,'<?'))
				$item = eval_phtml($item, $vars);
		}
	}
	unset($item);
}


// заменяет в строке $str все ключи массива  $array_replaces на соответствующие значения из этого массива
function str_replaces($str, $array_replaces)
{
	// преобразуем объект к массиву
	$array_replaces = (array)$array_replaces;

	$finds = array_keys($array_replaces);
	$replaces = array_values($array_replaces) ;

	$result =  str_replace (
				$finds,
				$replaces,
				$str
				);

	return $result;

}

// заменяет в строке $str все ключи массива $array_replaces в форме %key% (т.е. с добавлением % по краям) на соответствующие значения из этого массива, вместо массива может быть объект, и он будет преобразован в массив
function macro_replaces($str, $array_replaces)
{
	$array_replaces = (array)$array_replaces;

	$macro_replaces = array();
		foreach ($array_replaces as $key=>$val){
			if (is_string($val)){
        $macro_replaces["%$key%"] = $val;
      }
    }

	return 	str_replaces($str, $macro_replaces);
}




/* array_sortby(&$data, $sortby) - сортирует массив ассоциативных массивов (или массив стандартных объектов) по одному или нескольким свойствам, по возрастанию (ASC) или убыванию (DESC).

Функция ничего не возвращает, - первый аргумент (массив) передается по ссылке и сортируется.

Примеры:

	array_sortby($data, 'order, title, price DESC')

    $data= Articles::get_articles();
    array_sortby($data, 'show DESC, id ASC, title');
    pr($data);

*/
function array_sortby(&$data, $sortby)
{
    //pr(is_array($data));

    static $sort_funcs = array(); // init cache for sort functions
    if (!$sortby || !$data || !is_array($data)) return;

  //pr($data);

   // check cache for existing sort_func
    if (!empty($sort_funcs[$sortby]))
        $sort_func = $sort_funcs[$sortby];

        // create sort_func, if no sort_func in cache
    else
    {
       $code = "\$c=0; \n"; // start code of sorting functions
       $code.= "\$a=(array)\$a_arg; \$b=(array)\$b_arg; \n";// objects-arg to arrays

	   //pr(split(',', $sortby));

	   // пройдемся по каждому поля для сортировки и
	   // сформируем код строки функции для сравнения элементов массива
	   $fields = explode(',', $sortby);
	   foreach ( $fields as $field_order)
       {
            // check for empty string
            if (!trim($field_order)) continue;

            // extract fieldname and fieldorder (ASC or DESC)
            $field_order = trim(str_replace(array('  ','   ','     '),' ',$field_order));
            @list ($fieldname, $order) = explode(' ', $field_order.' ');
            //echo "fieldname='$fieldname', order='$order' <br>\n";


            if ($order != 'DESC') $order='ASC';

            // find the row of array for testing types of row-fields
            foreach ($data as $item)
				if (($item = (array)$item) && isset($item[$fieldname])) break;
			if (!isset($item[$fieldname])) continue;


            //pr($item);
            //pr($item[$fieldname]);

            // generate right expression for sorting

            // if nums - eval differens
            if(is_numeric($item[$fieldname]))
                $code.= "\$c = (@\$a['$fieldname'] - @\$b['$fieldname']); \n";

            // if no nums (string) - call comparsion func
            else
                $code.= "\$c = strcasecmp(@\$a['$fieldname'],@\$b['$fieldname']); \n";
            // invers result, if this field need DESC sorting
            if ($order == 'DESC')
                $code.= "\$c = -\$c; \n";

            // return $c, if there is difference
             $code.= "if(\$c) return \$c; \n";

       }
       // return zero, if no difference of all fields
       $code .= 'return $c;';
       //pr($code);


       // add sortfunc to cache
       $sort_func = $sort_funcs[$sortby] = create_function('$a_arg, $b_arg', $code);

    }
    //pr($data);

    // sort array
    $sort_func = $sort_funcs[$sortby];
    uasort($data, $sort_func);
    //pr($sort_func);
}





function array_filter_by_value($array, $test_key, $test_value, $condition='==')
{

    if (!is_array($array)) return;

    $result = array();

    foreach($array as $key=>$item) {

        if (!is_array($item) && !is_object($item)) continue;

        $test_item =  (array)$item;

        $condition_ok = false;


        // все условия для фильтраци элементов

        if ($condition == 'empty' ) { // пусто
            $condition_ok =  empty( $test_item[$test_key]);
        }

        if ($condition == '!empty' ) { // не пусто
            $condition_ok =  !empty( $test_item[$test_key]);
        }

        if ($condition == '==' ) { // равно
            $condition_ok =  ($test_item[$test_key] == $test_value);
        }

        if ($condition == '!=' ) { // не равно
            if ($test_item[$test_key] != $test_value) $condition_ok=true;
        }

        if ($condition == '*=' ) { // содержит
            $condition_ok =  ( strlen(strstr($test_item[$test_key], $test_value)) ) ;
        }


        if ($condition == '!*=' ) { // не содержит
            $condition_ok =  ( !strlen(strstr($test_item[$test_key], $test_value)) ) ;
        }

        if ($condition == '>' ) { // больше
            $condition_ok =  ($test_item[$test_key] > $test_value);
        }

        if ($condition == '<' ) { // меньше
            $condition_ok =  ($test_item[$test_key] > $test_value);
        }



        if ($condition_ok)
            $result[$key] = $item;

    }

    return $result;
}



// перемешивает массив, сохраняя ключи
function kshuffle(&$array)
{
    if(!is_array($array) || empty($array)) {
        return false;
    }
    $tmp = array();
    foreach($array as $key => $value) {
        $tmp[] = array('k' => $key, 'v' => $value);
    }
    shuffle($tmp);
    $array = array();
    foreach($tmp as $entry) {
        $array[$entry['k']] = $entry['v'];
    }
    return true;
}



// добавляет к первому массиву элементы второго
function array_extend(&$arr1 , $arr2)
{
	if (empty($arr1))		$arr1 = array();
	if (!is_array($arr1)) 	$arr1 = (array) $arr1;
	if (!is_array($arr2)) 	$arr2 = (array) $arr2;

	$arr1 = array_merge($arr1, $arr2);

  return ($arr1);

}


function array_unshift_assoc(&$arr, $key, $val)
{
   $arr = array_reverse($arr, true);
   $arr[$key] = $val;
   $arr = array_reverse($arr, true);
   return $arr;
}








/*
array_merge_recursive_distinct(&$a1, &$a2)

    merge 2 arrays , and overwrite existing keys in array1 by key=>value of array2

*/
function array_merge_recursive_distinct ( array &$array1, array &$array2 )
{
  $merged = $array1;

    foreach ( $array2 as $key => &$value )
    {
        if( is_array ($array2[$key])
            && isset ($merged[$key])
            && is_array ($merged [$key])
           )
        {
            $merged [$key] = array_merge_recursive_distinct ($merged[$key], $array2[$key]);
        }
        else
        {
            $merged[$key] = $value;
        }
    }

    return $merged;
}

function object_merge($obj1,$obj2)
{
	return (object)array_merge((array)$obj1, (array)$obj2);
}


/**
 * eval_template($template, $data) - исполняет файл шаблона $template и вставляет в него содержимое массива $data
 *
 * @param string $template - путь к файлу шаблона
 * @param string/array $data  - данные для вставки в шаблон.
 *      Если $data - строка, то данные вставляются в переменную $content.
 *      Если $data - одномерный ассоциативный массив, то данные вставляются в  переменые, соответсвтующие ключам массива.
 *      Если $data - двумерный массив, то в шаблон передается переменная $data без изменений, и шаблон должен будет обработать $data в цикле.
 *
 */
function eval_template($template_path, $data=array(), $additional_vars=array())
{
    // echo "$template_path<br/> ";

    //if (!$data) return '';// это должен определять сам шаблон

    // если $data - строка, назначаем строку в переменную $content
    if (is_string($data) && $data)
        $content=$data;

	// извлекаем переменные $vars, и они перекрывают одноименные переменные из $data
	if ($additional_vars && is_array($additional_vars))
		extract($additional_vars, EXTR_REFS | EXTR_SKIP ); 	//не перекрываем существующие переменные + извлекаем их как ссылки, чтобы шаблон мог менять

    // если $data - одномерный ассоциативный массив - извлекаем его свойства
    if ( is_object($data) || (is_array($data) && !isset($data[0])))
	{
    	extract( (array)$data, EXTR_SKIP ); //не перекрываем существующие переменные
	}


	// echo "$template_path<br/> ";

	// если шаблон не существует - кладем в результат свойство $content
    if (!$template_path || !file_exists($template_path))
    {
        $result = val($content);
    }
    else
    {// если шаблон существует - выполняем его и результат получаем их буфера

        $error_level_old = error_reporting(E_ALL&~ E_NOTICE);// для удобства позволяем использовать в шаблоне неинициализированные переменные и индексы без вывода ошибок

		//echo "$template_path | ";


            //pr(  has_bom(  file_get_contents($f) ) ? 1 : 2);


        check_file_utf8_bom($template_path); // устраняем UTF-8 BOM из шаблона

		ob_start();

        //header('Content-Type: text/html;charset=UTF-8');
        include ($template_path);
		//echo "$template_path | ";

        $result = ob_get_contents();
        ob_end_clean();

        error_reporting($error_level_old);// устанавливаем прежний уровень ошибок
    }

    return $result;
}


// Убираем метку BOM из начала файла в кодировке UTF8
function remove_utf8_bom($text="") {
   /*
    if(substr($text, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf))
    {

        $text= substr($text, 3);
    }
   */

    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);


    return $text;
}


// проверяет периодически файл на присутствие метки UTF-8 BOM, и елси она есть - удаляет её
function check_file_utf8_bom($file)
{
    $check_period =  60 * 60 ; // провярять файл 1 раз в час (3600 сек)

    if ( (time() - filemtime($file)) >= $check_period )
    {
        $f_content = file_get_contents($file);

        if (has_bom($f_content))
        {
            $ok = file_put_contents($file, remove_utf8_bom($f_content));

            if ($ok)
                trigger_error("Файл '{$file}' содержал UTF-8 BOM и был успешно исправлен." );
            else
                trigger_error("Файл '{$file}' содержит UTF-8 BOM, и его невозможно перезаписать и исправить." );


        }
    }
}



/*
Выводит пронумерованый код
*/
function print_code($code, $select_line='')
{
    $code = "\n".htmlspecialchars($code);
    $code = str_replace("\n","<li>\n",$code);
    $code = "<code>$code</ol></code>";
    echo $code;

}

// возвращает указаную строку из файла или текста
function get_line($str, $line)
{
	if (is_file($str))
	{
		$lines = file($str);
		return isset($lines[$line]) ? $lines[$line] : null;
	}

	$str = str_replace( array("\r\n", "\n\r", "\r"), "\n", $str);
	if(!strstr($str, "\n")) return $str;

	$lines = explode("\n", $str);
	return isset($lines[$line]) ? $lines[$line] : null;
}


/*
While it's easy to change timezones based on names or abbreviations, I haven't found any straightforward way of doing so using an offset integer.  This situation comes up if you're using AJAX to get information about a user's timezone; javascript's getTimezoneOffset() method just sends you an offset number. So, here's my clunky solution: an adaptation of chris' function at http://us.php.net/manual/en/function.timezone-name-from-abbr.php.
*/
function set_tz_by_offset($offset)
{
    $abbrarray = timezone_abbreviations_list();
    foreach ($abbrarray as $abbr)
    {
        foreach ($abbr as $city)
        {
            if ($city['offset'] == $offset) { // remember to multiply $offset by -1 if you're getting it from js
                   date_default_timezone_set($city['timezone_id']);
                   return true;
            }
        }
    }
    date_default_timezone_set("ust");
   return false;
}



function mail_utf8($to, $subject = '(No subject)', $message = '', $from='')
{
	$headers = 	 "MIME-Version: 1.0 \n"
				."Content-type: text/plain; charset=UTF-8 \n" ;
	if($from) $headers .= "From: $from \n";

	return mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $headers );
}


// отправляет в браузер HTTP-заголовок для установки кодировки документа
function header_charset($charset)
{
	$charset = strtolower($charset);

	if ( $charset=='utf' OR $charset=='utf8' OR $charset=='utf-8' OR
		 $charset=='utf 8' //OR $charset=='utf8' OR $charset==''
		)
		$charset='UTF-8';

	if ($charset=='win' OR $charset=='1251' OR $charset=='win-1251' OR
		$charset=='win 1251' OR $charset=='windows' OR $charset=='windows 1251'
		)
		$charset='windows-1251';

	header('Content-Type: text/html;charset=' . $charset);


}

function header_404()
{
	header("HTTP/1.1 404 Not Found");
}



function get_real_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}





// encoding detection (win1251, utf-8)
// используя иконв и мд5чексум, мы по-порядку ищем нужную нам кодировку из масива допустимых кодировочек ;) Легко и просто!
function detect_encoding($str) {
  return mb_detect_encoding($str);

  static $list = array( 'windows-1251', 'utf-8',);
  // static $list = array( 'utf-8', 'windows-1251',);

  foreach ($list as $item) {
    $sample = iconv($item, $item, $string);
    if (md5($sample) == md5($string))
      return $item;
  }
  return null;
}



// clearstatcache();
// $str = file_get_contents(dirname(__FILE__)."/View.class.php");
// pr(has_bom($str));
// pr(detect_encoding($str));
// pr( mb_detect_encoding($str.'a', 'utf-8, windows-1251')); //.'Тестирование'
// var_dump(($str));
// exit;
// echo is_utf8($str) ? 1 : 0;
// exit;
// pr( has_bom($str) ? 1:0);



// Проверяет, является ли кодировка строки - UTF-8 (with BOM or without BOM)
function is_utf8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
        $c=ord($str[$i]);
        if($c > 128){
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;
            if(($i+$bits) > $len) return false;
            while($bits > 1){
                $i++;
                $b=ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
}

// Проверяет, содержит ли UTF_8 строка в начале метку BOM
function has_bom($str) {
	return (int)( 0==strncmp("\xEF\xBB\xBF", $str, 3) );
}

// возращает TRUE, если переменная содержит текст, без учета HTML-тегов и неразрывных пробелов
function has_text(&$value)
{
	return (bool)trim(str_replace("&nbsp;"," ",strip_tags( val($value) )));
}

function is_email($value)
{
	return preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', trim($value) );
}

// Преобразование ДД.ММ.ГГГГ ЧЧ:ММ(:CC) => Unix timestamp
function datetime2unix($datetime)
{
	$datetime =  trim(str_replace(array('    ','   ','  '),' ',$datetime));

	// parse & extract  date,time,month,yeqar,day
	list($date,$time) = explode(' ',$datetime.' ');
	list($day,$month,$year) = explode('.',$date);
	list($hour,$minute,$second) = explode(':',$time.'::');
	//$second=0;
	//pr("$day,$month,$year,$hour,$minute");
	//pr("\$date=$date, \$time=$time");

	// calc timestamp
	$timestamp = mktime (
		(int)$hour, (int)$minute, (int)$second,
		$month ? $month : date('m'),
		$day ? $day : date('d'),
		(trim($year)===''?date('Y'):$year) // год=='' это текущий, год=='00' это 2000
		);

	// check for error data & return
	return  $timestamp > 0 ? $timestamp : ($timestamp<0 && $timestamp!=-1 ? 0 : false);

}

// Преобразование Unix timestamp => ДД.ММ.ГГГГ ЧЧ:ММ
function unix2datetime($timestamp, $return_array=false)
{
	//echo "$timestamp | ";
	$timestamp=intval($timestamp);
	//echo "$timestamp | ";

	$date = date('j.m.Y', $timestamp);	// '8.08.2008'
	$time = date('G:i:s', $timestamp); 	// '8:02:59'
	$datetime = $date .' '. $time;		// '8.08.2008 8:02:59'

	return $return_array? array($date,$time,$datetime) : $datetime;
}

// вычисляет среднее арифместическое массива


/***
	Array array_get( Array $arr, Mixed $key, Mixed $default = null)

	�?звлекает из массива $arr элепмент с индексом $key, а если его не сущесвтует, то возвращает $default

	Если $key - это массив, то из массива $arr извлекется несколько элементов, а если они не существуют, то вместо них подставляется $default (если это скаляр), или соответствующий $default[$i]  (если $default - это массив)

***/

function array_get($arr, $key, $default = null)
{
	if (is_array($key))
	{
		$result=array();
		foreach ($key as $i=>$k)
		{
			$d = is_array($default)? $default[$i] : $default;
			$result[$k] = array_get($arr, $k, $d);
		}
		return $result;
	}

	if (array_key_exists($key, $arr))
		return $arr[$key];
	else
		return $default;
}
/* test
$arr = array('a'=>'AAA', 'b'=>'BBB', 'c'=>'CCC');
pr(array_get($arr, array('a','c')));
 */



// преобразуем строку с параметрами (name=val&name2=val2) в массив
// если на входе массив - не изменяет его
// если на входе объект - преобразует его в массив
function query2array($str)
{
	// if(strstr($str,'shufflexp@gmail.com')) $test=1; else $test=0;


  if (is_object($str)) $str = (array)$str;




  if (is_string($str)) {
		parse_str($str, $str);


//if($test) pr($str);

		$str = undo_magic_quotes($str);


    //if($test) pr($str);
		}



  return $str;
}


// Преобразует многострочый текст в массив
function list2array($text)
{
  if(!$text) return array();

  $text = str_replace(array("\r\n","\n\r","\n"),'|||',$text);
  // pr('"'.$text.'"');
  // pr(explode('|||',$text));

  $text = array_trim(explode('|||',$text));

  // pr($text);
  return $text;
}




// trim each element of array
// unset empty values
function array_trim($arr, $charlist=null, $unset_empty=false)
{
    if ($charlist=='') $charlist=null;
    $result = array();

    foreach($arr as $key => $value)
	{
        if (is_array($value) || is_object($value))
        {
            $result[$key] = array_trim($value, $charlist, $unset_empty);
        }
		else
        {
            if ($unset_empty && trim($value)=='') continue;

            if ($charlist === NULL)
                $result[$key] = trim($value);
            else
                $result[$key] = trim($value, $charlist);
        }

    }
    return $result;
}


function array_average($arr, $dot_digits=10)
{
	if (!$arr || !sizeof($arr) ) return 0;
	return round(array_sum($arr) / sizeof($arr), $dot_digits);

}

// возвращает часть строки до последнего включения $needle, не включая  $needle
function strrstr($str, $needle)
{
    return substr($str, 0, strrpos($str,$needle));
}

// возвращает часть строки ПОСЛЕ первого $needle
function strstr_after($haystack, $needle)
{
    return substr(strstr($haystack, $needle), strlen($needle));
}



/**
	preg_find( $expr, $str ) - возращает совпавшую подстроку
  	$expr - регулярное выражение
	$str - строка

	Возвращает - подстроку из $str, совпавшую с первой группой() в выражениии $expr, а если групп нет - то со всем рег выражением.

 */
function preg_find( $expr, $str )
{
	preg_match($expr, $str, $found);

	if (isset($found[1])) return $found[1];
	if (isset($found[0])) return $found[0];
	return '';


}
// test:
// pr( preg_find('!<h1(?:>|\s[^>]*)>(.*)</h1>!i',
	// '<h1 1>Заголовок</h1>'
// ));


// возвращает строку из языкового массива
// примеры: echo lang('copy') . lang('files/copy') . lang('files/message/error')
// если на входе массив - то его значения добавляются к языковому массиву
function lang($id)
{
	static $lang_array = array();
	if (is_array($id)) {$lang_array = array_merge($lang_array, $id); return;}

	list($id1, $id2, $id3) = explode('/', $id.'//');
	if ($id1 && is_string($lang_array[$id1]))
		return $lang_array[$id1];
	if ($id2 && is_string($lang_array[$id1][$id2]))
		return $lang_array[$id1][$id2];
	if ($id3 && is_string($lang_array[$id1][$id2][$id3]))
		return $lang_array[$id1][$id2][$id3];
}



