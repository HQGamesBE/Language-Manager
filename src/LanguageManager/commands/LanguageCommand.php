<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\LanguageManager\commands;
use HQGames\Bridge\player\BridgePlayer;
use HQGames\Core\commands\commando\CommandoCommand;
use HQGames\Core\commands\commando\CommandoParameter;
use HQGames\Core\commands\commando\SoftEnumCache;
use HQGames\Core\player\Player;
use HQGames\LanguageManager\ChooseLanguageForm;
use HQGames\LanguageManager\LangaugePermissions;
use HQGames\LanguageManager\Permissions;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;


/**
 * Class LanguageCommand
 * @package HQGames\LanguageManager\commands
 * @author Jan Sohn / xxAROX
 * @date 22. July, 2022 - 22:43
 * @ide PhpStorm
 * @project Language-Manager
 */
class LanguageCommand extends CommandoCommand{
	public function __construct(){
		parent::__construct("language", "Change your language", "/language", ["lang"], [
			[
				CommandoParameter::enum("sub-command", new CommandEnum("language-test",  ["test"]), self::FLAG_NORMAL, true, LangaugePermissions::COMMAND_LANGUAGE_TEST[0]),
				CommandoParameter::standard("language-key", self::ARG_TYPE_STRING, self::FLAG_NORMAL, false, LangaugePermissions::COMMAND_LANGUAGE_TEST[0]),
			],
			[
				CommandoParameter::enum("sub-command", new CommandEnum("info",  ["info"]), self::FLAG_NORMAL, true, LangaugePermissions::COMMAND_LANGUAGE_INFO[0]),
				SoftEnumCache::getEnumByName("language_codes"),
				CommandoParameter::standard("language-code", self::ARG_TYPE_STRING, self::FLAG_NORMAL, false, LangaugePermissions::COMMAND_LANGUAGE_INFO[0]),
			],
			[
				CommandoParameter::enum("sub-command", new CommandEnum("reload",  ["reload"]), self::FLAG_NORMAL, true, LangaugePermissions::COMMAND_LANGUAGE_RELOAD[0]),
			]
		]);
	}

	protected function onRun(CommandSender|BridgePlayer $sender, string $typedCommand, array $args): void{
		if ($sender instanceof Player && !isset($args[0])) {
			$sender->sendForm(new ChooseLanguageForm($sender));
			return;
		}
	}
}
