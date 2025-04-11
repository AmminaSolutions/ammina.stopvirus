<?php


/**
 * @var ammina_stopvirus $this
 */

use Ammina\StopVirus\LangFile;

if (!check_bitrix_sessid()) {
	return;
}

global $APPLICATION;

LangFile::setMessagesFile('AMMINA_STOPVIRUS_INSTALL', __FILE__);

if (!is_array($this->errors) && ($this->errors === false || strlen($this->errors) <= 0)) {
	CAdminMessage::ShowNote(LangFile::message('INSTALL_OK'));
} else {
	$allErrors = implode('<br/>', $this->errors);
	CAdminMessage::ShowMessage([
		'TYPE' => 'ERROR',
		'MESSAGE' => LangFile::message('INSTALL_ERROR'),
		'DETAILS' => $allErrors,
		'HTML' => true,
	]);
}
if ($ex = $APPLICATION->GetException()) {
	CAdminMessage::ShowMessage([
		'TYPE' => 'ERROR',
		'MESSAGE' => LangFile::message('INSTALL_ERROR'),
		'HTML' => true,
		'DETAILS' => $ex->GetString(),
	]);
}
?>

<p><a href="/bitrix/admin/ammina.stopvirus.settings.php?lang=<?= LANGUAGE_ID ?>"><?= LangFile::message('INSTALL_GOTO_SETTINGS') ?></a></p>

<form action="<?= $APPLICATION->GetCurPage() ?>">
	<input type="hidden" name="lang" value="<?= LANG ?>"/>
	<input type="submit" name="" value="<?= LangFile::message('INSTALL_BACK') ?>"/>
</form>