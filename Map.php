<?php
/**
 * PHP array class that accepts *any* value as the key
 * (null, false, true, float, arrays, objects, resources)
 *
 * Example:
 * $map = new Map();
 * $map[null] = 1;
 * $map[false] = 2;
 * $map[1.5] = 3;
 * $arr = array(1, 2, 3);
 * $map[$arr] = 4;
 * $im = imagecreate(10,10); // requires GD
 * $map[$im] = 5;
 * echo $map[null].$map[false].$map[1.5].$map[array(1, 2, 3)].$map[$im]; // "1234"
 */
class Map implements ArrayAccess {
	private $map = array();

	public function __construct() {
		$this->map = array();
	}

	private function os($offset) {
		
		if (is_resource($offset)) {
			$t = array(
				"type" => "resource",
				"value" => get_resource_type($offset) . " - " . ((string) $offset)
			);			
		} else if (is_object($offset)) {
			$t = array(
				"type" => "object",
				"value" => spl_object_hash($offset)
			);
		} else {
			$t = array(
				"type" => "basic",
				"value" => $offset
			);
		}
		
		return serialize($t);
	}

	public function offsetSet($offset, $value) {
		$offset = $this->os($offset);
		if (is_null($offset)) {
			$this->map[] = $value;
		} else {
			$this->map[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		$offset = $this->os($offset);
		return isset($this->map[$offset]);
	}

	public function offsetUnset($offset) {
		$offset = $this->os($offset);
		unset($this->map[$offset]);
	}

	public function offsetGet($offset) {
		$offset = $this->os($offset);
		return isset($this->map[$offset]) ? $this->map[$offset] : null;
	}
	
}
