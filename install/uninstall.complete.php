<?php

/**
 * @var ammina_stopvirus $this
 */

use Ammina\StopVirus\LangFile;

if (!check_bitrix_sessid()) {
	return;
}

global $APPLICATION, $errors;
LangFile::setMessagesFile('AMMINA_STOPVIRUS_INSTALL', __FILE__);

if (!is_array($this->errors) && ($this->errors === false || strlen($this->errors) <= 0)) {
	CAdminMessage::ShowNote(LangFile::message("UNINSTALL_OK"));
} else {
	$allErrors = implode('<br/>', $errors);
	CAdminMessage::ShowMessage([
		'TYPE' => 'ERROR',
		'MESSAGE' => LangFile::message('UNINSTALL_ERROR'),
		'DETAILS' => $allErrors,
		'HTML' => true,
	]);
}
?>
<form action="<?= $APPLICATION->GetCurPage() ?>">
	<input type="hidden" name="lang" value="<?= LANG ?>"/>
	<input type="submit" name="" value="<?= LangFile::message('UNINSTALL_BACK') ?>"/>
</form>
