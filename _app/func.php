<?php



// парсит texy разметку
function parse_texy($texyText, $imageRoot=NULL, &$vars=NULL)
{


	// выбираем из texy файла переменные вида: {{varname: value }}
	$vars = [];
	if (preg_match_all('~{{([^:]+):([^}]+)}}~', $texyText, $matches, PREG_SET_ORDER)) {

		//pr($matches);

		foreach ($matches as $match) {
			$vars[ $match[1] ]  = $match[2];
		}

		//pr($vars);

		$texyText = preg_replace('~{{([^:]+):([^}]+)}}~', '', $texyText);
	}




	// конфигурируем Texy
	$texy = new Texy();

	// URL-папка для картинок
	$texy->imageModule->root = $imageRoot;


	//pr($branch, $folder );


	// Настраиваем парсинг заголовков
	$texy->headingModule->top = 1;   // set headings top limit
	//$texy->headingModule->balancing = Texy\Modules\HeadingModule::FIXED; // fixed-leveling

	$texy->headingModule->levels['*'] = 0;  // (h1)
	$texy->headingModule->levels['='] = 1;  // (h2)
	$texy->headingModule->levels['-'] = 2;  // (h3)

	if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/') {
		$texy->headingModule->levels['-'] = 1;  // (h2)
	}



	$content = $texy->process($texyText);
	$title = $texy->headingModule->title;

	if (isset($vars['maintitle']))
		$title = $vars['maintitle'];


	//pr($title, $content);



	return $content;

}


