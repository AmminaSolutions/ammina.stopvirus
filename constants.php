<?php
if (!defined("AMMINA_STOPVIRUS_ROOT")) {
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/modules/ammina.stopvirus')) {
		define('AMMINA_STOPVIRUS_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/local/modules/ammina.stopvirus/');
	} else {
		define('AMMINA_STOPVIRUS_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/ammina.stopvirus/');
	}
}
