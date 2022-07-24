<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\LanguageManager\player;
use DateTime;
use Exception;
use HQGames\Core\player\Player;
use HQGames\LanguageManager\Language;
use HQGames\LanguageManager\LanguageManager;
use HQGames\MySQLConnection;
use JetBrains\PhpStorm\ArrayShape;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\TextPacket;


/**
 * Trait LanguagePlayerTrait
 * @package HQGames\LanguageManager\player
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 15:43
 * @ide PhpStorm
 * @project Language-Manager
 */
trait LanguagePlayerTrait{
	protected string $lang_code = "null";

	protected function language_loadData(array $data): void{
		$this->lang_code = LanguageManager::getInstance()->getLanguage($data["lang_code"] ?? LanguageManager::$FALLBACK)->getLocale(); // NOTE: to prevent errors, we use fallback language, if the specific language is not found anymore.
	}

	#[ArrayShape([ "lang_code" => "string" ])]
	protected function language_saveData(): array{
		return [
			"lang_code" => $this->lang_code,
		];
	}

	/**
	 * Function getLangCode
	 * @return string
	 */
	public function getLangCode(): string{
		return $this->lang_code;
	}

	public function getLang(): Language{
		return LanguageManager::getInstance()->getLanguage($this->getLangCode());
	}

	public function setLang(Language $lang): void{
		/** @var Player $this */
		$this->lang_code = LanguageManager::getInstance()->getLanguage($lang->getLocale())->getLocale(); // NOTE: to prevent errors, we use fallback language, if the specific language is not found anymore.
		MySQLConnection::getInstance()->getMedoo()->update("players", ["lang_code" => $lang->getLocale()], ["identifier" => $this->getIdentifier()]);
		$this->lang_code = $lang->getLocale();
		// TODO: [Implement Logger]:   Logger::getInstance()->log("Player Logger");
	}

	/**
	 * Function translate
	 * @param string $str
	 * @param null|array $params
	 * @return string
	 */
	public function translate(string $str, ?array $params = []): string{
		if ((bool)($this->getSettings()->getEntry("%player-settings.language-debug")->getFormElement()->getValue()) === true){
			return $str;
		}
		return $this->getLang()->translate($str, $params);
	}

	/**
	 * Function sendDebugMessage
	 * @param string $message
	 * @return void
	 */
	public function sendDebugMessage(string $message): void{
		/** @var Player $this */
		$pk = new TextPacket();
		$pk->type = TextPacket::TYPE_SYSTEM;
		$pk->message = "§d[Debug]: " . $this->server->getLanguage()->translateString($message);
		$this->getNetworkSession()->sendDataPacket($pk);
	}

	/**
	 * Function sendMessage
	 * @param Translatable|string $message
	 * @param null|array $params
	 * @return void
	 */
	public function sendMessage(Translatable|string $message, ?array $params = []): void{
		/** @var Player $this */
		if ($message instanceof Translatable) {
			$this->sendTranslation($message->getText(), $message->getParameters());
			return;
		}
		if (!$this->getSettings()->getEntry("%player-settings.language-debug")) {
			$words = explode(" ", $message);
			foreach ($words as $k => $word) {
				if ($word[0] === '%') {
					try {
						$words[$k] = $this->translate($word, $params);
					} catch (Exception $e) {
						continue;
					}
				}
			}
			$message = implode(" ", $words);
			foreach ($params as $k => $param) {
				$param = strval($param);
				if ($param[0] === '%') {
					try {
						$params[$k] = $this->translate($param);
					} catch (Exception $e) {
						continue;
					}
				}
			}
		} else {
			$this->sendDebugMessage($message . (count($params) > 0 ? ": " . implode(", ", array_map(fn(mixed $_) => strval($_), $params)) : ""));
			return;
		}
		$pk = new TextPacket();
		$pk->type = TextPacket::TYPE_RAW;
		$pk->message = $this->server->getLanguage()->translateString($message);
		$this->getNetworkSession()->sendDataPacket($pk);
	}

	/**
	 * Function formatTime
	 * @param int $time
	 * @param null|int $time2
	 * @param null|bool $seconds
	 * @param null|bool $short
	 * @param null|bool $spaceAfterChar
	 * @return string
	 */
	public function formatTime(int $time, ?int $time2 = 0, ?bool $seconds = false, ?bool $short = false, ?bool $spaceAfterChar = true): string{
		$dt1 = new DateTime("@$time2");
		$dt2 = new DateTime("@$time");
		$diff = $dt1->diff($dt2);
		$diffSeconds = (int)$diff->format("%s");
		$diffMinutes = (int)$diff->format("%i");
		$diffHours = (int)$diff->format("%h");
		$diffDays = (int)$diff->format("%d");
		$diffMonths = (int)$diff->format("%m");
		$diffYears = (int)$diff->format("%y");
		$str = "";
		if ($diffYears > 0) {
			if ($diffYears != 1) {
				$str .= "{$diffYears}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.year.short" : "time.years");
			} else {
				$str .= "{$diffYears}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.year.short" : "time.year");
			}
		}
		if ($diffMonths > 0) {
			if ($diffYears > 0) {
				$str .= ", ";
			}
			if ($diffMonths != 1) {
				$str .= "{$diffMonths}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.month.short" : "time.months");
			} else {
				$str .= "{$diffMonths}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.month.short" : "time.month");
			}
		}
		if ($diffDays > 0) {
			if ($diffYears > 0 || $diffMonths > 0) {
				$str .= ", ";
			}
			if ($diffDays != 1) {
				$str .= "{$diffDays}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.day.short" : "time.days");
			} else {
				$str .= "{$diffDays}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.day.short" : "time.day");
			}
		}
		if ($diffHours > 0) {
			if ($diffYears > 0 || $diffMonths > 0 || $diffDays > 0) {
				$str .= ", ";
			}
			if ($diffHours != 1) {
				$str .= "{$diffHours}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.hour.short" : "time.hours");
			} else {
				$str .= "{$diffHours}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.hour.short" : "time.hour");
			}
		}
		if ($diffMinutes > 0) {
			if ($diffYears > 0 || $diffMonths > 0 || $diffDays > 0 || $diffHours > 0) {
				$str .= ", ";
			}
			if ($diffMinutes != 1) {
				$str .= "{$diffMinutes}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.minute.short"
						: "time.minutes");
			} else {
				$str .= "{$diffMinutes}" . ($spaceAfterChar ? " " : "") . $this->translate($short ? "time.minute.short"
						: "time.minute");
			}
		}
		if ($diffMinutes == 0) {
			$seconds = true;
		}
		if ($seconds) {
			if ($diffSeconds > 0) {
				if ($diffYears > 0 || $diffMonths > 0 || $diffDays > 0 || $diffHours > 0 || $diffMinutes > 0) {
					$str .= ", ";
				}
				if ($diffSeconds != 1) {
					$str .= "{$diffSeconds}" . ($spaceAfterChar ? " " : "") . $this->translate($short
							? "time.second.short" : "time.seconds");
				} else {
					$str .= "{$diffSeconds}" . ($spaceAfterChar ? " " : "") . $this->translate($short
							? "time.second.short" : "time.second");
				}
			}
		}
		return $str;
	}
}
