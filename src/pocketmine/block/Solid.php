<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\block;

abstract class Solid extends Block{

	const POWERED_NONE = 0;
	const POWERED_WEAKLY = 1;
	const POWERED_STRONGLY = 2;
	
	public function isSolid(){
		return true;
	}
	
	/** @todo */
	public function getPoweredState() {
		// !IMPORTANT! bottom should be first in the list
		static $offsets = [
			[0, -1, 0], // bottom
			[1, 0, 0], // north
			[-1, 0, 0], // south
			[0, 0, 1], // east
			[0, 0, -1], // west
		];
		foreach ($offsets as $direction => $offset) {
			$blockId = $this->level->getBlockIdAt($this->x + $offset[0], $this->y + $offset[1], $this->z + $offset[2]);
			switch ($blockId) {
				case self::REDSTONE_WIRE:
					if ($offset[1] == 0) { // all except bottom
						$wire = $this->level->getBlock(new Vector3($this->x + $offset[0], $this->y + $offset[1], $this->z + $offset[2]));
						if ($wire->getDamage() > 0) {
							return self::POWERED_WEAKLY;
						}
					}
					break;
				case self::REDSTONE_TORCH_ACTIVE;
					if ($offset[1] == -1) { // only bottom
						return self::POWERED_STRONGLY;
					}
					break;
			}
		}
		return self::POWERED_NONE;
	}
}