<?php

$messages = [
	'PAGE_TITLE' => "Настройки модуля \"Ammina StopVirus: Защита от внедрения вирусов на сайт\"",
	'TAB' => [
		'EDIT1' => [
			'TAB' => 'Основные параметры',
			'TITLE' => 'Общие параметры',
		],
	],
	'MENU' => [
		'RETURN_LIST' => [
			'TEXT' => 'Вернуться',
			'TITLE' => 'Вернуться в журнал попыток внедрения вирусов',
		],
	],
	'FIELD' => [
		'ACTIVE' => 'Активировать защиту',
		'STORE_HOST' => 'Определять и сохранять имя хоста',
		'TTL_DETECTED' => 'Время хранения журнала с попытками внедрения вирусов, дней',
		'TTL_NO_DETECTED' => 'Время хранения журнала без попыток внедрения вирусов, дней',
		'MESSAGE' => 'Сообщение при попытке внедрения вируса',
		'DETECT_FILE_INCLUDE' => 'Проверка подключения файла защиты',
		'INCLUDE_RUN_FILE' => 'Подключить файл защиты',
		'DETECT_FILE_ERROR' => "Не обнаружено подключение файла защиты /bitrix/tools/ammina.stopvirus.php в файле init.php.\n\n" .
			"Вы можете самостоятельно подключить его в файле init.php (каталог /bitrix/php_interface/ или /local/php_interface/ в зависимости от того, что на вашем сайте используется). Строка для подключения:\ninclude_once(\$_SERVER['DOCUMENT_ROOT'] . '/bitrix/tools/ammina.stopvirus.php');\n\n" .
			"Либо при включении галочки ниже и сохранении настроек модуль подключит строку автоматически.",
		'DETECT_FILE_OK' => 'Файл защиты подключен',
		'DETECT_OLDCODE_INCLUDE' => 'Проверка наличия старого кода защиты',
		'DETECT_OLDCODE_ERROR' => [
			'BITRIX' => 'Обнаружен устаревший код защиты с форума битрикс в файле /bitrix/php_interface/init.php - необходимо удалить его',
			'LOCAL' => 'Обнаружен устаревший код защиты с форума битрикс в файле /local/php_interface/init.php - необходимо удалить его',
		],
		'OLD_CODE_TITLE'=>'Устаревший код защиты выглядит примерно так:',
		'OLD_CODE' => '
$cont = \'\';
if ($_SERVER[\'REQUEST_METHOD\'] == \'POST\') {
    $cont = mb_strtolower(file_get_contents(\'php://input\'));
} else {
    $cont = mb_strtolower($_SERVER[\'QUERY_STRING\']);
}
$cont .= "\n";
if (function_exists(\'getallheaders\')) {
    foreach (getallheaders() as $name => $value) {
        $cont .= $name . \':\' . $value . "\n";
    }
}

if (strpos($cont, \'base64\') !== false || strpos($cont, \'file_put_contents\') !== false || strpos($cont, \'file_get_contents\') !== false || strpos($cont, \'fwrite\') !== false || (strpos($cont, \'action=getphpversion\') !== false && strpos($cont, \'path=\') !== false && strpos($cont, \'echo\') !== false)) {
    die(\'Все, лесом ребята\');
}
',

	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_SETTINGS');
