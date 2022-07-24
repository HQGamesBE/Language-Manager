<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\LanguageManager\scheduler;
use Closure;
use Error;
use HQGames\LanguageManager\Language;
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;


/**
 * Class FetchLanguageAsyncTask
 * @package HQGames\LanguageManager\scheduler
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 21:09
 * @ide PhpStorm
 * @project Language-Manager
 */
class FetchLanguageAsyncTask extends AsyncTask{
	public function __construct(protected Closure $onCompletion){
	}

	/**
	 * Function onRun
	 * @return void
	 */
	public function onRun(): void{
		$result = Internet::getURL("https://api.github.com/repos/HQGamesBE/Language/contents/eng.json", 10, [
			"Accept: application/vnd.github.v3.raw",
			"User-Agent: HQGamesBE/Language-Manager",
			"Authorization: token ". getenv("GH_TOKEN")
		]);
		if (is_null($result) || $result->getCode() !== 200) {
			$this->setResult(new Error("Failed to fetch language file"));
			return;
		}
		$langs = json_decode($result->getBody(), true);
		$languages = [];
		foreach ($langs as $lang_code) {
			$result = Internet::getURL("https://api.github.com/repos/HQGamesBE/Language/contents/{$lang_code}.json", 10, [
				"Accept: application/vnd.github.v3.raw",
				"User-Agent: HQGamesBE/Language-Manager",
				"Authorization: token " . getenv("GH_TOKEN")
			]);
			if (is_null($result) || $result->getCode() !== 200) {
				$this->setResult(new Error("Failed to fetch language file for {$lang_code}"));
				return;
			}
			$lang = json_decode($result->getBody(), true);
			$languages[$lang_code] = new Language($lang);
		}
		$this->setResult($languages);
	}

	public function onCompletion(): void{
		@($this->onCompletion)($this->getResult());
	}
}
