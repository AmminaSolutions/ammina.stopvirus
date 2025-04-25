<?php
include_once(__DIR__ . '/constants.php');

use Bitrix\Main\Loader;

//for bitrix 18
$loaderParams = (new ReflectionClass(\Bitrix\Main\Loader::class))->getMethod('registerNamespace')->getParameters();
if (count($loaderParams) === 3) {
	CModule::AddAutoloadClasses('ammina.stopvirus', [
		'\\Ammina\\StopVirus\\LangFile' => "lib/LangFile.php",
		'\\Ammina\\StopVirus\\Detector' => "lib/Detector.php",
		'\\Ammina\\StopVirus\\Migrator' => "lib/Migrator.php",
		'\\Ammina\\StopVirus\\Settings' => "lib/Settings.php",
		'\\Ammina\\StopVirus\\Db\\AttemptsTable' => "lib/Db/AttemptsTable.php",
		'\\Ammina\\StopVirus\\Db\\RulesTable' => "lib/Db/RulesTable.php",
		'\\Ammina\\StopVirus\\Db\\SignaturesTable' => "lib/Db/SignaturesTable.php",
		'\\Ammina\\StopVirus\\Orm\\Attempts' => "lib/Orm/Attempts.php",
		'\\Ammina\\StopVirus\\Orm\\Rules' => "lib/Orm/Rules.php",
		'\\Ammina\\StopVirus\\Orm\\Signatures' => "lib/Orm/Signatures.php",
		'\\Ammina\\StopVirus\\Orm\\AttemptsCollection' => "lib/Orm/AttemptsCollection.php",
		'\\Ammina\\StopVirus\\Orm\\RulesCollection' => "lib/Orm/RulesCollection.php",
		'\\Ammina\\StopVirus\\Orm\\SignaturesCollection' => "lib/Orm/SignaturesCollection.php",
	]);
} else {
	\Bitrix\Main\Loader::registerNamespace("Ammina\\StopVirus", AMMINA_STOPVIRUS_ROOT . "/lib");
}

(\Ammina\StopVirus\Settings::getInstance())->checkDeleteOldData();
(\Ammina\StopVirus\Settings::getInstance())->checkUpdates();