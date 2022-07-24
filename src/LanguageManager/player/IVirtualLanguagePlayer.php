<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\LanguageManager\player;
use HQGames\LanguageManager\Language;
use pocketmine\lang\Translatable;


/**
 * Interface IVirtualLanguagePlayer
 * @package HQGames\LanguageManager\player
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 15:39
 * @ide PhpStorm
 * @project Language-Manager
 */
interface IVirtualLanguagePlayer{
	/**
	 * Function getLangCode
	 * @return string
	 */
	public function getLangCode(): string;

	/**
	 * Function getLang
	 * @return Language
	 */
	public function getLang(): Language;

	/**
	 * Function setLang
	 * @param Language $lang
	 * @return void
	 */
	public function setLang(Language $lang): void;

	/**
	 * Function translate
	 * @param string $key
	 * @param array $params
	 * @return string
	 */
	public function translate(string $key, array $params = []): string;

	/**
	 * Function sendMessage
	 * @param Translatable|string $message
	 * @param array $params
	 * @return string
	 */
	public function sendMessage(Translatable|string $message, array $params = []): string;
}
