<?php
return new class {
	public function run(): bool
	{
		if (!$this->runTableAttemptsAddFieldMatches()) {
			return false;
		}
		if (!$this->runTableSignaturesCreate()) {
			return false;
		}
		if (!$this->runTableRulesCreate()) {
			return false;
		}
		if (!$this->runCopyAdminFiles()) {
			return false;
		}
		if (!$this->runCopyToolsFiles()) {
			return false;
		}
		return true;
	}

	protected function runTableAttemptsAddFieldMatches(): bool
	{
		global $DB;
		$fields = $DB->GetTableFields('am_stopvirus_attempts');
		if (!array_key_exists('MATCH_SIGNATURES', $fields)) {
			$sql = 'ALTER TABLE `am_stopvirus_attempts` ADD `MATCH_SIGNATURES` TEXT NULL DEFAULT NULL AFTER `DATA_BODY`;';
			$result = $DB->Query($sql, true);
			if (!$result) {
				return false;
			}
		}
		return true;
	}

	protected function runTableSignaturesCreate(): bool
	{
		global $DB;
		if (!$DB->TableExists('am_stopvirus_signatures')) {
			$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `am_stopvirus_signatures` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `NAME` varchar(255) DEFAULT NULL,
    `SIGNATURES` TEXT NULL DEFAULT NULL,
    `IS_DEFAULT` CHAR(1) NOT NULL DEFAULT 'N',
    PRIMARY KEY (`ID`),
    INDEX `IX_IS_DEFAULT` (`IS_DEFAULT`)
);
EOF;
			$result = $DB->Query($sql, true);
			if (!$result) {
				return false;
			}
		}
		return true;
	}

	protected function runTableRulesCreate(): bool
	{
		global $DB;
		if (!$DB->TableExists('am_stopvirus_rules')) {
			$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `am_stopvirus_rules` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `NAME` varchar(255) DEFAULT NULL,
    `ACTIVE` CHAR(1) NOT NULL DEFAULT 'Y',
    `SORT` INT NOT NULL DEFAULT '1000',
    `ACTION` CHAR(1) NOT NULL DEFAULT 'D',
    `USE_DEFAULT_SIGNATURE` CHAR(1) NOT NULL DEFAULT 'Y',
    `SIGNATURE_ID` INT NULL DEFAULT NULL,
    `STORE_POST_BODY` CHAR(1) NOT NULL DEFAULT 'Y',
    `MEMORY_LIMIT` INT NULL DEFAULT NULL,
    `PAGE_RULES` LONGTEXT NULL DEFAULT NULL,
    `IP_RULES` LONGTEXT NULL DEFAULT NULL,
    `IP_RULES_VALUES` LONGTEXT NULL DEFAULT NULL,
    PRIMARY KEY (`ID`),
    INDEX `IX_ACTIVE` (`ACTIVE`),
    INDEX `IX_SORT` (`SORT`)
);
EOF;
			$result = $DB->Query($sql, true);
			if (!$result) {
				return false;
			}
		}
		return true;
	}

	protected function runCopyAdminFiles(): bool
	{
		return CopyDirFiles(AMMINA_STOPVIRUS_ROOT . "/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true, true);
	}

	protected function runCopyToolsFiles(): bool
	{
		return CopyDirFiles(AMMINA_STOPVIRUS_ROOT . "/install/tools", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/tools", true, true);
	}

};