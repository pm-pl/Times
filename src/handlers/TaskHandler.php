<?php

/*
 *   _______ _
 *  |__   __(_)
 *     | |   _ _ __ ___   ___  ___
 *     | |  | | '_ ` _ \ / _ \/ __|
 *     | |  | | | | | | |  __/\__ \
 *     |_|  |_|_| |_| |_|\___||___/
 *
 * Copyright (C) 2024 pixelwhiz
 *
 * This software is distributed under "GNU General Public License v3.0".
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see <https://opensource.org/licenses/GPL-3.0>.
 */

namespace pixelwhiz\times\handlers;

use pixelwhiz\times\Loader;
use pixelwhiz\times\math\DayRange;
use pixelwhiz\times\math\Time;
use pixelwhiz\times\TimeManager;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class TaskHandler extends Task {

    private Loader $plugin;
    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun(): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $world = $player->getWorld();
            $time = $world->getTime();
            if ($time >= 168000) {
                $world->setTime(0);
            }
            
            if (isset($this->plugin->useClock[$player->getName()])) {
                $player = Server::getInstance()->getPlayerExact($player->getName());
                $day = TimeManager::getCurrentDay($player->getWorld());
                $time = TimeManager::getCurrentTime($player->getWorld());
                $message = str_replace(["{time}", "{day}"], [$time, $day], $this->plugin->getConfig()->get("format"));
                $player->sendTip($message);
            }
        }
    }


}