<?php

/**
 * @var CMain $APPLICATION
 * @var CUser $USER
 */

use Ammina\StopVirus\Db\AttemptsTable;
use Ammina\StopVirus\LangFile;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.stopvirus');
require_once(AMMINA_STOPVIRUS_ROOT . "prolog.php");

LangFile::loadMessages(__FILE__, 'AMMINA_STOPVIRUS_ATTEMPTS_VIEW');

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(LangFile::message("ACCESS_DENIED", ''));
}

////////////////////////////
$sTabID = 'ammina_stopvirus_attempts_view';
$pageLinkList = '/bitrix/admin/ammina.stopvirus.attempts.php';
$pageLinkEdit = '/bitrix/admin/ammina.stopvirus.attempts.view.php';

$context = (\Bitrix\Main\Application::getInstance())->getContext();
$request = $context->getRequest();

$ID = $request->get('ID') ? (int)$request->get('ID') : 0;
if ($ID <= 0) {
	LocalRedirect($pageLinkList . '?lang=' . LANGUAGE_ID);
}
$save = trim($request->get('save'));
$apply = trim($request->get('apply'));
$requestMethod = $context->getServer()->getRequestMethod();
$message = null;


$isSavingOperation = ($requestMethod === "POST" && (!empty($save) || !empty($apply)) && check_bitrix_sessid());
$needFieldsRestore = $requestMethod === "POST" && !$isSavingOperation;

$currentItem = AttemptsTable::getById($ID)->fetchObject();
if (!$currentItem) {
	LocalRedirect($pageLinkList . '?lang=' . LANGUAGE_ID);
}

$aTabs = [
	[
		'DIV' => 'edit1',
		'TAB' => LangFile::message('TAB_EDIT1_TAB'),
		'TITLE' => LangFile::message('TAB_EDIT1_TITLE'),
	],
	[
		'DIV' => 'edit2',
		'TAB' => LangFile::message('TAB_EDIT2_TAB'),
		'TITLE' => LangFile::message('TAB_EDIT2_TITLE'),
	],
	[
		'DIV' => 'edit3',
		'TAB' => LangFile::message('TAB_EDIT3_TAB'),
		'TITLE' => LangFile::message('TAB_EDIT3_TITLE'),
	],
	[
		'DIV' => 'edit4',
		'TAB' => LangFile::message('TAB_EDIT4_TAB'),
		'TITLE' => LangFile::message('TAB_EDIT4_TITLE'),
	],
];
$tabControl = new CAdminForm($sTabID, $aTabs);

$APPLICATION->SetTitle(LangFile::message("PAGE_TITLE", null, ['ID' => $currentItem->getId()]));

$aMenu = [
	[
		'TEXT' => LangFile::message('MENU_RETURN_LIST_TEXT'),
		'TITLE' => LangFile::message('MENU_RETURN_LIST_TITLE'),
		'LINK' => $pageLinkList . '?lang=' . LANGUAGE_ID,
		'ICON' => 'btn_list',
	],
];
$contextMenu = new CAdminContextMenu($aMenu);
////////////////////////////

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

////////////////////////////
$contextMenu->Show();

if (!empty($strError)) {
	CAdminMessage::ShowMessage($strError);
}

$tabControl->BeginEpilogContent();
echo bitrix_sessid_post();
?>
	<input type="hidden" name="ID" value="<?= $currentItem->getId() ?>"/>
	<style>
        .ammina-format-virusstop {
            text-wrap: wrap;
            word-break: break-all;
            max-width: 100vw;
        }

        .ammina-format-virusstop .virus-code {
            font-weight: bold;
            color: #ff0000;
            font-size: 1.3em;
            text-decoration: underline;
        }
	</style>
<?
$tabControl->EndEpilogContent();

$tabControl->Begin([
	"FORM_ACTION" => $APPLICATION->GetCurPage() . "?lang=" . LANGUAGE_ID,
]);

$tabControl->BeginNextFormTab();
$tabControl->AddViewField('ID', LangFile::message('FIELD_ID') . ':', $currentItem->getId());
$tabControl->AddViewField('IS_DETECT_VIRUS', LangFile::message('FIELD_IS_DETECT_VIRUS') . ':', $currentItem->getIsDetectVirus() ? LangFile::message('FIELD_IS_DETECT_VIRUS_VALUE_Y') : LangFile::message('FIELD_IS_DETECT_VIRUS_VALUE_N'));
$tabControl->AddViewField('DATE_CREATE', LangFile::message('FIELD_DATE_CREATE') . ':', $currentItem->getDateCreate());
$tabControl->AddViewField('REQUEST_TYPE', LangFile::message('FIELD_REQUEST_TYPE') . ':', $currentItem->getRequestType());
$tabControl->AddViewField('HTTP_HOST', LangFile::message('FIELD_HTTP_HOST') . ':', $currentItem->getHttpHost());
$tabControl->AddViewField('REQUEST_URI', LangFile::message('FIELD_REQUEST_URI') . ':', $currentItem->getRequestUri());
$tabControl->AddViewField('SCRIPT_FILENAME', LangFile::message('FIELD_SCRIPT_FILENAME') . ':', $currentItem->getScriptFilename());
$tabControl->AddViewField('FROM_IP', LangFile::message('FIELD_FROM_IP') . ':', $currentItem->getFromIp());
$tabControl->AddViewField('FROM_HOST', LangFile::message('FIELD_FROM_HOST') . ':', $currentItem->getFromHost());
$tabControl->AddViewField('FROM_USER_AGENT', LangFile::message('FIELD_FROM_USER_AGENT') . ':', $currentItem->getFromUserAgent());
$tabControl->AddViewField('MATCH_SIGNATURES', LangFile::message('FIELD_MATCH_SIGNATURES') . ':', nl2br(htmlspecialchars(implode("\n", $currentItem->getMatchSignatures() ?? []))) . '&nbsp');

$tabControl->BeginNextFormTab();

$tabControl->BeginCustomField('DATA_HEADER', LangFile::message('FIELD_DATA_HEADER'), false);
$content = [];
$data = $currentItem->getDataHeader();
if (!is_array($data)) {
	$data = [];
}
foreach ($data as $header => $value) {
	$content[] = $header . ': ' . $value;
}
?>
	<tr class="heading">
		<td colspan="2"><?= LangFile::message('FIELD_DATA_HEADER') ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<div class="ammina-format-virusstop">
				<?= (Ammina\StopVirus\Detector::getInstance())->formatContent(implode("\n", $content), $currentItem->getMatchSignatures()) ?>
			</div>
		</td>
	</tr>
<?
$tabControl->EndCustomField('DATA_HEADER');

$tabControl->BeginNextFormTab();
$tabControl->BeginCustomField('DATA_BODY', LangFile::message('FIELD_DATA_BODY'), false);
?>
	<tr class="heading">
		<td colspan="2"><?= LangFile::message('FIELD_DATA_BODY') ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<div class="ammina-format-virusstop">
				<?= (\Ammina\StopVirus\Detector::getInstance())->formatContent($currentItem->getDataBody()['content'] ?? '', $currentItem->getMatchSignatures()) ?>
			</div>
		</td>
	</tr>
<?
$tabControl->EndCustomField('DATA_BODY');

$tabControl->BeginNextFormTab();
$tabControl->BeginCustomField('DATA_QUERY', LangFile::message('FIELD_DATA_QUERY'), false);
?>
	<tr class="heading">
		<td colspan="2"><?= LangFile::message('FIELD_DATA_QUERY') ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<div class="ammina-format-virusstop">
				<?= (\Ammina\StopVirus\Detector::getInstance())->formatContent($currentItem->getDataQuery()['content'] ?? '', $currentItem->getMatchSignatures()) ?>
			</div>
		</td>
	</tr>
<?
$tabControl->EndCustomField('DATA_QUERY');

$tabControl->Buttons([
	"btnSave" => false,
	"btnApply" => false,
	"btnCancel" => false,
]);
$tabControl->Show();

//$tabControl->ShowWarnings($tabControl->GetName(), $message);

///////////////////////////

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
