<?php

include_once(dirname(__DIR__) . '/constants.php');
//for bitrix 18
$loaderParams = (new ReflectionClass(\Bitrix\Main\Loader::class))->getMethod('registerNamespace')->getParameters();
if (count($loaderParams) === 3) {
	\Bitrix\Main\Loader::registerNamespace("Ammina\\StopVirus", 'ammina.stopvirus', AMMINA_STOPVIRUS_ROOT . "/lib");
} else {
	\Bitrix\Main\Loader::registerNamespace("Ammina\\StopVirus", AMMINA_STOPVIRUS_ROOT . "/lib");
}

use Ammina\StopVirus\LangFile;
use \Bitrix\Main;

LangFile::loadMessages(__FILE__, "AMMINA_STOPVIRUS_INSTALL");

class ammina_stopvirus extends CModule
{
	public $errors = false;

	/**
	 * @throws Main\IO\InvalidPathException
	 */
	public function __construct()
	{
		/**
		 * @var array $arModuleVersion
		 */
		include(__DIR__ . "/version.php");
		$this->MODULE_ID = 'ammina.stopvirus';
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = LangFile::message("MODULE_NAME");
		$this->MODULE_DESCRIPTION = LangFile::message("MODULE_DESC");

		$this->PARTNER_NAME = GetMessage('AMMINA_STOPVIRUS_INSTALL_PARTNER_NAME');// LangFile::message("PARTNER_NAME");
		$this->PARTNER_URI = GetMessage('AMMINA_STOPVIRUS_INSTALL_PARTNER_URI');//LangFile::message("PARTNER_URI");
	}

	/**
	 * @return void
	 * @throws Main\IO\InvalidPathException
	 * @throws Main\LoaderException
	 */
	public function DoInstall(): void
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
		Main\ModuleManager::registerModule($this->MODULE_ID);
		Main\Loader::includeModule($this->MODULE_ID);
		$APPLICATION->IncludeAdminFile(LangFile::message("INSTALL_TITLE"), AMMINA_STOPVIRUS_ROOT . "/install/install.complete.php");
	}

	/**
	 * @return void
	 * @throws Main\IO\InvalidPathException
	 */
	public function DoUninstall(): void
	{
		global $APPLICATION, $step;
		$step = (int)$step;
		if ($step <= 1) {
			$APPLICATION->IncludeAdminFile(LangFile::message("UNINSTALL_TITLE"), AMMINA_STOPVIRUS_ROOT . "/install/uninstall.step1.php");
		} elseif ($step === 2) {
			$this->UnInstallDB([
				"savedata" => $_REQUEST["savedata"] === 'Y',
			]);
			$this->UnInstallFiles();
			Main\ModuleManager::unRegisterModule($this->MODULE_ID);
			$APPLICATION->IncludeAdminFile(LangFile::message("UNINSTALL_TITLE"), AMMINA_STOPVIRUS_ROOT . "/install/uninstall.complete.php");
		}
	}

	/**
	 * @param array $arParams
	 * @return bool
	 */
	public function InstallDB(array $arParams = []): bool
	{
		global $DB, $APPLICATION;
		$strSqlFile = AMMINA_STOPVIRUS_ROOT . "/install/db/install.sql";
		if (file_exists($strSqlFile)) {
			$errors = $DB->RunSQLBatch($strSqlFile);
			if (!empty($errors)) {
				$APPLICATION->ThrowException(implode("", $errors));
				return false;
			}
		}

		return true;
	}

	/**
	 * @param array $arParams
	 * @return bool
	 * @throws Main\ArgumentException
	 * @throws Main\ObjectPropertyException
	 * @throws Main\SystemException
	 */
	public function UnInstallDB(array $arParams = []): bool
	{
		global $DB, $APPLICATION;

		$strSqlFile = AMMINA_STOPVIRUS_ROOT . "/install/db/uninstall.sql";
		if (array_key_exists("savedata", $arParams) && !$arParams["savedata"] && file_exists($strSqlFile)) {
			$errors = $DB->RunSQLBatch($strSqlFile);
			if (!empty($errors)) {
				$APPLICATION->ThrowException(implode("", $errors));
				return false;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function InstallEvents(): bool
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function UnInstallEvents(): bool
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function InstallFiles(): bool
	{
		CopyDirFiles(AMMINA_STOPVIRUS_ROOT . "/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true, true);
		CopyDirFiles(AMMINA_STOPVIRUS_ROOT . "/install/tools", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/tools", true, true);
		return true;
	}

	/**
	 * @return bool
	 */
	public function UnInstallFiles(): bool
	{
		DeleteDirFiles(AMMINA_STOPVIRUS_ROOT . "/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
		DeleteDirFiles(AMMINA_STOPVIRUS_ROOT . "/install/tools", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/tools");
		return true;
	}
}
