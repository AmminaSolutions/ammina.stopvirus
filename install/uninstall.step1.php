<?php

global $APPLICATION;

use Ammina\StopVirus\LangFile;

LangFile::setMessagesFile('AMMINA_STOPVIRUS_INSTALL', __FILE__);

?>
<form action="<?= $APPLICATION->GetCurPage() ?>">
	<?= bitrix_sessid_post() ?>
	<input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>"/>
	<input type="hidden" name="id" value="ammina.stopvirus"/>
	<input type="hidden" name="uninstall" value="Y"/>
	<input type="hidden" name="step" value="2"/>
	<?= LangFile::message('UNINSTALL_WARNING') ?>
	<p><strong><?= LangFile::message('UNINSTALL_SAVE') ?></strong>
	</p>
	<p>
		<input type="checkbox" name="savedata" id="savedata" value="Y" checked="checked"/>
		<label for="savedata"><?= LangFile::message('UNINSTALL_SAVE_TABLES') ?></label>
	</p>
	<input type="submit" value="<?= LangFile::message('UNINSTALL_DELETE') ?>"/>
</form>
