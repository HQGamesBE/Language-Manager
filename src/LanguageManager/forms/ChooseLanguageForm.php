<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\LanguageManager\forms;
use HQGames\Core\player\Player;
use HQGames\forms\elements\Button;
use HQGames\forms\types\MenuForm;
use HQGames\LanguageManager\Language;
use HQGames\LanguageManager\LanguageManager;


/**
 * Class ChooseLanguageForm
 * @package HQGames\LanguageManager\forms
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 20:45
 * @ide PhpStorm
 * @project Language-Manager
 */
class ChooseLanguageForm extends MenuForm{
	protected Player $player;


	public function __construct(Player $holder) {
		$this->player = $holder;
		parent::__construct(
			"%forms.choose-language.title",
			"%forms.choose-language.text",
			array_map(function (Language $language): Button{
				return new Button($language->getColoredName() . PHP_EOL . "§8§oby " . implode(", ", $language->getContributors()),
					function (Player $player) use ($language): void{
						$player->setLang($language);
						$player->sendMessage("%message.language.changed", [$language->getName()]);
					}
				);
			}, LanguageManager::getInstance()->getLanguages()), function (Player $player): void{});
	}
}
