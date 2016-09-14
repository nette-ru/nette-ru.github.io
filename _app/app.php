<?php

/**
 * Генератор статического сайта nette-ru - русская версия nette.org
 *
 * @todo Встроить конвертер texy2html (web-content-converter)
 * @todo обеспечить правильный парсинг ссылок
 *       [api:Nette\Caching\Storages\FileStorage]
 *       [expiration rules |#expiration-and-invalidation]
 *       [DateTime|php:DateTime.construct]
 *       и других форматов указанных здесь: @link https://nette.org/en/syntax
 * @todo обеспечить парсинг {{комманд}} и дальнейшее применение установленных в них значений
 *
 * @todo Перевести все страницы первого интереса
 * @todo Добавить свой дополнительный css (и в статичный сайт)
 *
 */


// region Configs
{

	define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);


	// Корневая папка генерируемого статичного сайта
	$STATIC_ROOT	= dirname(DOCUMENT_ROOT) . '/nette-ru';

	// Корневая папка репозитория с контентом сайта (внутри - папка для каждой ветви nette.org, doc-2.4, ...)
	$CONTENT_ROOT = realpath(DOCUMENT_ROOT .'/../nette-web-content');


	// Массив со свойствами страниц сайта
	$PAGES = include "pages.php";


	// выводим все ошибки на локальном сайте
	if($_SERVER['REMOTE_ADDR']==='127.0.0.1') error_reporting(-1);


}
// endregion





// region Includes
{

	// подключаем все нужные библиотеки
	require __DIR__ .'/lib/web-content-convertor/vendor/autoload.php';
	require __DIR__ .'/lib/DomQuery/DomQuery.class.php';
	require __DIR__ .'/lib/functions.php';
	require __DIR__ .'/func.php';

}
// endregion





// region Parse_Request
{

	$request = (object)[
		'root'     => DOCUMENT_ROOT,
		'host'     => $host = str_replace('www.', '', $_SERVER['HTTP_HOST']),
		'uri'      => $uri = $_SERVER['REQUEST_URI'],
		'url'      => "http://{$host}{$uri}",
		'query'    => strstr($uri, '?') ? substr($uri, strpos($uri, '?') + 1) : '',
		'path'     => $path = strstr($uri, '?') ? substr($uri, 0, strpos($uri, '?')) : $uri,
		'file'     => substr($path, -1) === '/' ? $path . 'index.html' : $path,
		'segments' => explode('/', trim($path, '/ ')),
		'isIndex'  => $path === '/' || $path === '/index.html',
	];


	//pr($_SERVER, $request);

}
// endregion




// region Routing
{

	// вычисляем свойства запрошенной страницы
	$page = isset($PAGES[$request->file]) ? $PAGES[$request->file] : [];
	$page = (object)$page;


	// вычисляем сгенерированный файл - в статическом сайте nette-ru
	$staticFile	= ($STATIC_ROOT . $request->file);

	// вычисляем файл - источник разметки
	$markupFile =  (DOCUMENT_ROOT . "/data" . $request->file); // data | markup | pages | saved | samples |


	// вычисляем файл - источник контента ( parse(*.texy) || $(*.html).content )
	$contentFile = isset($page->content) ?  realpath($CONTENT_ROOT ."/". $page->content) : NULL ;


	// error 404
	if (!file_exists($contentFile) && !file_exists($markupFile )) { // && !file_exists($staticFile)

		exit('Запрошенный файл не найден: ' . $request->file );
	}


	//pr($staticFile, $markupFile, $contentFile);

}
// endregion





// region Echo_StaticPage
{

	// выводим сгенерированную статичную страницу, если исходные данные не изменялись с момента генерации

	if (    file_exists($staticFile)
		&&  filemtime($staticFile) >= filemtime($contentFile)
		&&  filemtime($staticFile) >= filemtime($markupFile)
	){
		echo file_get_contents($staticFile);
		exit;
	}

}
// endregion







// region Get_Content
{

	$markupDoc = \Aurim\DomQuery::loadHtml( file_get_contents($markupFile));


	// Извлекаем контент из html-файла, если texy-файл недоступен
	if (!$contentFile ) {

		$page->content =  $markupDoc->find('.content')->html();

	} else {

		// Или извлекаем контент из texy-файла
		$page->content =  file_get_contents($contentFile);

		//pr($page->content);


		if (substr($contentFile, -5) === '.texy') {


			// Вычисляяем базовый URL для картинок (doc-2.4, www, tracy, latte, ...)
			$contentFolder = str_replace([$CONTENT_ROOT, 'nette.org'], '' , $contentFile );
			$contentFolder = trim($contentFolder,'/');
			$contentFolder = substr($contentFolder,0, strpos($contentFolder,'/'));

			$imageRoot = '//files.nette.org/git/' . $contentFolder;
			//pr($imageRoot);

			$page->content = parse_texy($page->content, $imageRoot, $vars);


			pr($page->content);

		}

	}


	$page->title   = val($vars['maintitle']) ?: $markupDoc->find('title')->html();



}
// endregion






// region Inject_Content
{

	// извлекаем данные для страницы
	// и внедряем их в шаблон


	$doc = \Aurim\DomQuery::loadHtml( file_get_contents("template.php"));

	// исполняем шаблон
	// ob_start(); include "template.php"; $html = ob_get_clean();


	// Перемещаем контент и настройки шаблона из страницы-образца - в шаблон

	// extract content
	$doc->find('.content')->html(
		$page->content
	);

	// extract title
	$doc->find('title')->html(
		$page->title
	);

	// extract homepage style
	if ($request->isIndex) { // $page->isIndex
		$doc->find('.content-wrap')->addClass('mainHomepage')->removeClass('main');
	}

	// extract homepage banner

	//$banner = $markupDoc->find('.banner')->html();


	if (val($page->banner)) {
		$doc->find('.banner-wrap')->html( "<DIV class=banner> $banner </DIV>");
	}

	// extract&set html[class] (style for logo) of site subdomein/division
	$doc->find('html')->class =  $markupDoc->find('html')->class;


	$doc->find('.content')->attr('class',
		$markupDoc->find('.content')->attr('class')
	);

	// extract sidebar from <aside>
	if ($markupDoc->find('.content')->hasClass('has-sidebar')) {
		$doc->find('.content')->addClass('has-sidebar');
		$doc->find('.aside-wrap')->html(
			$markupDoc->find('.page aside.main')->outerHtml()
		);
	}

	// styles for addons
	if (strstr( $request->file, 'addons')) {
		$doc->find('link[data-href*="addons"]')->each(
			function($el){
				/** @var Aurim\DomQuery $el */
				$el->href = $el->{'data-href'} ;
			});
	}



}
// endregion


//pr($html);



// region Add_BaseURLs
{

	// вычисляем базовый хост для всех ссылок без хоста
	$baseMap = [
		// 1й сегмент пути  => базовый хост
		'*'      => 'https://nette.org',
		'doc'    => 'https://doc.nette.org',
		'latte'  => 'https://latte.nette.org',
		'tracy'  => 'https://tracy.nette.org',
		'tester' => 'https://tester.nette.org',
		'addons' => 'https://addons.nette.org',
		'pla'    => 'https://pla.nette.org',

	];

	$baseHost = isset( $baseMap[$request->segments[0]] )
		? $baseMap[$request->segments[0]]
		: $baseMap['*'];

	//pr($requestFile, $baseHost);


	// Добавляем базовый хост - ко всем ссылкам без хоста, для которых нет перевода
	$links = $doc->find('[href^="/"]')
		->not('[href^="//"]')
		->each(function($el) use($baseHost) {
			$el->href = $baseHost . $el->href;
		})
	;


}
// endregion



// region Rewrite_SiteLinks
{

	// Подключаем массив со списком переведённых страниц [ внешний URL => URL на сайте ]
	$translatedPages = include "pages.php";


	// Заменяем переведённые URL на локальные страницы сайта
	$links = $doc->find('a')
	             ->each(function($el) use($translatedPages) {

		             /** @var \Aurim\DomQuery $el */

		             // нормализуем ссылку - к виду ключей в массиве $translatedPages
		             $href= strtr('^' .$el->href, [
			             '^https://'=> '',
			             '^http://' => '',
			             '^//'      => '',
			             'www.'     => '',
			             '.org/en'  => '.org',
			             '/2.4'     => '',
						 '^'        => '',
		             ]);

			         list($href, $fragment) = explode('#', $href.'#');

		             $href = rtrim($href, '/');

		             $el->{'normal-href'} = $href;

		             // проверяем, есть ли такая страница в переводах, и заменяем ссылку
		             if (isset($translatedPages[ $href ])) {

			             $el->addClass('translated');

			             $el->href = $translatedPages[ $href ] . ($fragment ? "#$fragment" : "" ) ;

		             }
	             })
	;

	//pr(''. $links);





}
// endregion



//pr($doc);


// region Echo_SitePage
{


	// выводим сгенерированную страницу
	echo $doc ;


	// записываем сгенерированную страницу в статический сайт

	//file_put_contents($staticFile, $html);

}
// endregion














