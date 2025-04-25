<?php

namespace Ammina\StopVirus;


LangFile::loadMessages(__FILE__, "AMMINA_STOPVIRUS_MIGRATOR");

class Migrator
{
	static protected $instance = null;

	protected array $migrations = [
		'1.1.0',
	];

	public static function getInstance(): Migrator
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

	public function check(): bool
	{
		$settings = Settings::getInstance();
		$dbVersion = $settings->optionDbVersion();
		$moduleVersion = $settings->moduleVersion();
		if ($dbVersion == $moduleVersion) {
			return true;
		}
		if ($settings->isStartedDbMigration()) {
			return false;
		}
		$settings->startDbMigration();

		foreach ($this->migrations as $version) {
			if (version_compare($dbVersion, $version, ">=")) {
				continue;
			}
			$migrationFile = AMMINA_STOPVIRUS_ROOT . '/install/migrations/' . $version . '.php';
			if (file_exists($migrationFile)) {
				$class = include($migrationFile);
				if (!$class->run()) {
					break;
				}
				$settings->setOptionDbVersion($version);
				$dbVersion = $version;
			}
		}

		$settings->endtDbMigration();
		return true;
	}
}
