<?php

namespace Ammina\StopVirus;

use Ammina\StopVirus\Db\AttemptsTable;

LangFile::loadMessages(__FILE__, "AMMINA_STOPVIRUS_SETTINGS");

class Settings
{
	static protected $instance = null;

	public static function getInstance(): Settings
	{
		if (!isset(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	private function __clone()
	{
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	private function __wakeup()
	{
		throw new \Exception("Cannot unserialize a singleton.");
	}

	public function optionActive(): bool
	{
		return \COption::GetOptionString("ammina.stopvirus", "active", 'N') === 'Y';
	}

	public function setOptionActive(bool $active): self
	{
		\COption::SetOptionString("ammina.stopvirus", "active", $active ? 'Y' : 'N');
		return $this;
	}

	public function optionStoreHost(): bool
	{
		return \COption::GetOptionString("ammina.stopvirus", "store.host", 'Y') === 'Y';
	}

	public function setOptionStoreHost(bool $storeHost): self
	{
		\COption::SetOptionString("ammina.stopvirus", "store.host", $storeHost ? 'Y' : 'N');
		return $this;
	}

	public function optionTtlDetected(): int
	{
		return \COption::GetOptionInt("ammina.stopvirus", "ttl_detected", '365');
	}

	public function setOptionTtlDetected(int $ttlDetected): self
	{
		if ($ttlDetected < 1) {
			$ttlDetected = 1;
		}
		\COption::SetOptionInt("ammina.stopvirus", "ttl_detected", $ttlDetected);
		return $this;
	}

	public function optionTtlNoDetected(): int
	{
		return \COption::GetOptionInt("ammina.stopvirus", "ttl_no_detected", '14');
	}

	public function setOptionTtlNoDetected(int $ttlNoDetected): self
	{
		if ($ttlNoDetected < 1) {
			$ttlNoDetected = 1;
		}
		\COption::SetOptionInt("ammina.stopvirus", "ttl_no_detected", $ttlNoDetected);
		return $this;
	}

	public function optionMessage(): string
	{
		return \COption::GetOptionString("ammina.stopvirus", "message", 'Hacker, stop!');
	}

	public function setOptionMessage(string $message): self
	{
		\COption::SetOptionString("ammina.stopvirus", "message", trim($message));
		return $this;
	}

	public function detectRunFileInclude(): bool
	{
		return (defined('AMMINA_STOPVIRUS_RUN') && AMMINA_STOPVIRUS_RUN === true);
	}

	public function includeRunFile(): bool
	{
		global $APPLICATION;
		if ($this->detectRunFileInclude()) {
			return true;
		}
		$toFile = $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init.php';
		if (!file_exists($toFile)) {
			$toFile = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/init.php';
		}
		if (file_exists($toFile)) {
			$content = '<?php include_once($_SERVER[\'DOCUMENT_ROOT\'] . \'/bitrix/tools/ammina.stopvirus.php\');?>' . file_get_contents($toFile);
		} else {
			$content = '<?php include_once($_SERVER[\'DOCUMENT_ROOT\'] . \'/bitrix/tools/ammina.stopvirus.php\');';
		}
		$APPLICATION->SaveFileContent($toFile, $content);
		return true;
	}

	public function checkDeleteOldData(): void
	{
		global $DB;
		$oldCheck = \COption::GetOptionInt("ammina.stopvirus", "old_check_delete", 0);
		if ($oldCheck < time()) {
			\COption::SetOptionInt("ammina.stopvirus", "old_check_delete", time() + 3600 * 12);

			$DB->Query('DELETE FROM `' . AttemptsTable::getTableName() . '` WHERE `IS_DETECT_VIRUS`=\'N\' AND `DATE_CREATE` < \'' . date('Y-m-d H:i:s', time() - $this->optionTtlNoDetected() * 86400) . '\';', true);
			$DB->Query('DELETE FROM `' . AttemptsTable::getTableName() . '` WHERE `IS_DETECT_VIRUS`=\'Y\' AND `DATE_CREATE` < \'' . date('Y-m-d H:i:s', time() - $this->optionTtlDetected() * 86400) . '\';', true);
		}
	}

	public function checkUpdates(): void
	{
		$oldCheck = \COption::GetOptionInt("ammina.stopvirus", "old_check_updates", 0);
		if ($oldCheck < time()) {
			\COption::SetOptionInt("ammina.stopvirus", "old_check_updates", time() + 3600 * 24);
			$update = false;
			$currentVersion = trim(file_get_contents(AMMINA_STOPVIRUS_ROOT . '/version'));
			$remoteVersion = null;
			if (function_exists('curl_init')) {
				$curl = curl_init('https://raw.githubusercontent.com/AmminaSolutions/ammina.stopvirus/refs/heads/main/version');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($curl);
				$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				if ((int)$code === 200 && strlen($result) > 0) {
					$remoteVersion = trim($result);
				}
				curl_close($curl);
			} else {
				$oHttpClient = new \Bitrix\Main\Web\HttpClient(
					[
						'redirect' => true,
						'redirectMax' => 10,
						'version' => '1.1',
						'disableSslVerification' => true,
						'waitResponse' => true,
						'socketTimeout' => 10,
						'streamTimeout' => 20,
						'charset' => "UTF-8",
					]
				);
				$response = trim($oHttpClient->get('https://raw.githubusercontent.com/AmminaSolutions/ammina.stopvirus/refs/heads/main/version'));
				$status = $oHttpClient->getStatus();
				if ((int)$status === 200 && strlen($response) > 0) {
					$remoteVersion = trim($response);
				}
			}
			if (is_null($remoteVersion) || strlen($remoteVersion) <= 0) {
				\COption::SetOptionInt("ammina.stopvirus", "old_check_updates", time() + 3600 * 12);
			} elseif (version_compare($currentVersion, $remoteVersion, '<')) {
				$update = true;
			}
			if ($update) {
				\CAdminNotify::Add(
					[
						//'MODULE_ID' => "",
						//'TAG' => "",
						'MESSAGE' => '<b>' . LangFile::message('UPDATE_TITLE') . '</b><br>' . LangFile::message('UPDATE_MESSAGE'),
						'ENABLE_CLOSE' => "Y",
						'PUBLIC_SECTION' => "N",
						'NOTIFY_TYPE' => "M",
					]
				);
			}
		}
	}

	public function optionDbVersion(): string
	{
		return \COption::GetOptionString("ammina.stopvirus", "db.version", '1.0.0');
	}

	public function setOptionDbVersion(string $dbVersion): self
	{
		\COption::SetOptionString("ammina.stopvirus", "db.version", $dbVersion);
		return $this;
	}

	public function moduleVersion(): string
	{
		return trim(file_get_contents(AMMINA_STOPVIRUS_ROOT . '/version'));
	}

	public function isStartedDbMigration(): bool
	{
		if (\COption::GetOptionString("ammina.stopvirus", "db.migration.start", 'N') === 'Y' && ((time() - \COption::GetOptionInt("ammina.stopvirus", "db.migration.start.time", 0)) < 60)) {
			return true;
		}
		return false;
	}

	public function startDbMigration(): self
	{
		\COption::SetOptionString("ammina.stopvirus", "db.migration.start", 'Y');
		\COption::SetOptionInt("ammina.stopvirus", "db.migration.start.time", time());
		return $this;
	}

	public function endtDbMigration(): self
	{
		\COption::SetOptionString("ammina.stopvirus", "db.migration.start", 'N');
		return $this;
	}

	public function detectOldCodeFromForumInBitrixInit(): bool
	{
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/init.php')) {
			return $this->detectOldCodeFromForum(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/init.php'));
		}
		return false;
	}

	public function detectOldCodeFromForumInLocalInit(): bool
	{
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init.php')) {
			return $this->detectOldCodeFromForum(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init.php'));
		}
		return false;
	}

	protected function detectOldCodeFromForum($content): bool
	{
		if (strpos($content, 'mb_strtolower(file_get_contents(\'php://input\'))') !== false) {
			return true;
		}
		if (strpos($content, 'strpos($cont, \'file_put_contents\')') !== false) {
			return true;
		}
		return false;
	}
}