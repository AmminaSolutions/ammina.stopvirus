<?

use Ammina\StopVirus\LangFile;

if (!\Bitrix\Main\Loader::includeModule('ammina.stopvirus')) {
	return false;
}
LangFile::loadMessages(__FILE__, 'AMMINA_STOPVIRUS_MENU');
global $USER;
if ($USER->IsAdmin()) {

	$aMenu = [
		'parent_menu' => 'global_menu_services',
		'section' => 'ammina.stopvirus',
		'sort' => 10000,
		'text' => LangFile::message('MAIN_TEXT'),
		'title' => LangFile::message('MAIN_TITLE'),
		'icon' => 'ammina_stopvirus_menu_icon',
		'page_icon' => 'ammina_stopvirus_page_icon',
		'items_id' => 'menu_ammina_stopvirus',
		'items' => [
			[
				'text' => LangFile::message('ATTEMPTS_TEXT'),
				'title' => LangFile::message('ATTEMPTS_TITLE'),
				'items_id' => 'menu_ammina_stopvirus_attempts',
				'url' => 'ammina.stopvirus.attempts.php?lang=' . LANGUAGE_ID,
				'more_url' => [
					'ammina.stopvirus.attempts.view.php',
				],
			],
			[
				'text' => LangFile::message('SETTINGS_TEXT'),
				'title' => LangFile::message('SETTINGS_TITLE'),
				'items_id' => 'menu_ammina_stopvirus_settings',
				'url' => 'ammina.stopvirus.settings.php?lang=' . LANGUAGE_ID,
				'more_url' => [],
			],
		],
	];

	return $aMenu;
}
return false;
