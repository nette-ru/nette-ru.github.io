<?php
/**
 * Массив со свойствами страниц сайта
 *
 * Структура элемента массива
 *
 * '/запрашиваемый файл ($request->path)' => [
 *      'original'  => 'нормализованный url оригинальной страницы на nette.org, будет заменяться на локальную ссылку сайта',
 *      'content'   => 'путь к файлу с $page->content (если *.texy, то будет преобраован в html)',
 *      'banner'    => 'путь к файлу с $banner',
 *      ... // любые другие своства страницы, спользуемые в шаблоне
 * ]
 *
 * Нормализованный url страницы nette.org указывается без:
 *  https:// , http:// , // ,  www. , /en , /2.4 ,  #{fragment} , и без конечного слеша '/'
 *
 */


return [

	'/index.html' => [
		'original'  => 'nette.org',
		'content'   => 'nette.org/www/ru/homepage.texy',
		'banner'    => 'nette.org/www/ru/banner.html',
	],

	'/download.html' => [
		'original'  => 'nette.org/download',
	    'content'   => 'nette.org/www/ru/download.texy',
	],

	'/about.html' => [
		'original'  => 'nette.org/about',
		'content'   => '/nette.org/www/ru/about.texy',
	],

	'/doc/index.html' => [
		'original'  => 'doc.nette.org',
		//'content'   => 'doc-2.4/ru/about.texy',
	],

	'/doc/getting-started.html' => [
		'original'  => 'doc.nette.org/getting-started',
		//'content'   => 'doc-2.4/ru/getting-started.texy',
	],





];
