<?php

$messages = [
	'UPDATE' => [
		'TITLE' => 'Обновление модуля Ammina.StopVirus.',
		'MESSAGE' => 'Обновите модуль Ammina.StopVirus для полноценной защиты сайта. Обновить модуль можно по ссылке <a href="/bitrix/admin/update_system_partner.php?lang=ru">Marketplace</a>, либо через репозитарий на <a href="https://github.com/AmminaSolutions/ammina.stopvirus">GitHub</a>.',
	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_SETTINGS');
