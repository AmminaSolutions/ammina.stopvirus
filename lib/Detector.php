<?php

namespace Ammina\StopVirus;

use Ammina\StopVirus\Orm\Attempts;

class Detector
{
	static protected $instance = null;

	protected $checkSubstrings = [
		'base64',
		'file_put_contents',
		'file_get_contents',
		'fwrite',
		'fread',
		'action=getphpversion',
		'echo',
		'accesson',
		'path=wget',
		'path=curl',
	];

	public static function getInstance(): Detector
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

	public function run(): void
	{
		if (!(Settings::getInstance())->optionActive()) {
			return;
		}
		$isDetected = $this->detectRequest();
		if ($isDetected || (Settings::getInstance())->optionStoreAll()) {
			$host = null;
			if ((Settings::getInstance())->optionStoreHost()) {
				$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			}
			$headers = [];
			if (function_exists('getallheaders')) {
				$headers = getallheaders();
			}
			$attempt = new Attempts();
			$query = $_SERVER['QUERY_STRING'] ?? null;
			$body = file_get_contents('php://input') ?? null;
			if (!is_null($query)) {
				$query = ['content' => $query];
			}
			if (!is_null($body)) {
				$body = ['content' => $body];
			}
			$attempt
				->setRequestType($_SERVER['REQUEST_METHOD'] ?? 'cli')
				->setHttpHost($_SERVER['HTTP_HOST'] ?? null)
				->setRequestUri($_SERVER['REQUEST_URI'] ?? null)
				->setScriptFilename($_SERVER['SCRIPT_FILENAME'] ?? null)
				->setFromIp($_SERVER['REMOTE_ADDR'] ?? null)
				->setFromHost($host)
				->setFromUserAgent($_SERVER['HTTP_USER_AGENT'] ?? null)
				->setIsDetectVirus($isDetected)
				->setDataHeader($headers)
				->setDataQuery($query)
				->setDataBody($body)
				->save();
		}
		if ($isDetected && !(Settings::getInstance())->optionDisableBlockRequests()) {
			die((Settings::getInstance())->optionMessage());
		}
	}

	public function detectRequest(): bool
	{
		if ((Settings::getInstance())->optionAllowSystemRequests() && $this->isSystemRequest()) {
			return false;
		}
		$checkContent = mb_strtolower($_SERVER['QUERY_STRING']);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$checkContent .= mb_strtolower(file_get_contents('php://input'));
		}
		if (function_exists('getallheaders')) {
			foreach (getallheaders() as $name => $value) {
				$checkContent .= mb_strtolower($name) . ':' . mb_strtolower($value) . "\n";
			}
		}

		foreach ($this->checkSubstrings as $substring) {
			if (strpos($checkContent, $substring) !== false) {
				return true;
			}
		}

		return false;
	}

	public function formatContent(string $content): string
	{
		foreach ($this->checkSubstrings as $substring) {
			$content = $this->markCode($substring, '#VIRUS#', '#/VIRUS#', $content);
		}
		$content = nl2br(htmlspecialchars($content));
		$content = str_replace(['#VIRUS#', '#/VIRUS#'], ['<span class="virus-code">', '</span>'], $content);
		return $content;
	}

	protected function markCode($needle, $preString, $postString, $haystack)
	{
		$offset = 0;
		$needleCount = mb_strlen($needle);
		while (($end = mb_stripos($haystack, $needle, $offset)) !== false) {
			$startHaystack = mb_substr($haystack, 0, $end);
			$endHaystack = mb_substr($haystack, $end + $needleCount, mb_strlen($haystack));
			$oldContent = mb_substr($haystack, $end, $needleCount);
			$replace = $preString . $oldContent . $postString;
			$offset = mb_strlen($startHaystack . $replace);
			$haystack = $startHaystack . $replace . $endHaystack;
		}
		return $haystack;
	}

	protected function isSystemRequest(): bool
	{
		global $APPLICATION;
		$pageUrl = $APPLICATION->GetCurPage();
		if ($pageUrl === '/bitrix/services/main/ajax.php') {
			if (in_array($_REQUEST['action'], [
				'security.xscan.scan',
			])) {
				return true;
			}
		}
		return false;
	}
}