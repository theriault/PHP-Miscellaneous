<?php
/**
 * Class that can get/set any non-static property or call any non-static
 * method of an object regardless of visibility or location in inheritance
 * chain.
 */
class HereditaryProxy
{
	private $object;
	private $reflection;
	private $silence = false;
	
	/**
	 * @param object $object Object to proxy
	 * @param bool $silence Whether accessing unexisting methods and
	 *  properties should return null instead of throwing exceptions.
	 */
	public function __construct($object, $silence = false)
	{
		$this->silence = (bool) $silence;
		$this->object = (object) $object;
		$this->reflection = new ReflectionObject($this->object);
	}
	
	/**
	 * Internal. Used to return an accessible ReflectionProperty on the
	 * proxied object.
	 */
	private function getProperty($reflection, $name)
	{
		if ($reflection->hasProperty($name))
		{
			$prop = $reflection->getProperty($name);
		}
		else
		{	
			while ($reflection = $reflection->getParentClass())
			{
				if ($reflection->hasProperty($name))
				{
					$prop = new ReflectionProperty($reflection->getName(), $name);
					break;
				}	
			}
			if ($reflection === false)
			{
				return null;
			}
		}
		$prop->setAccessible(true);
		return $prop;	
	}

	/**
	 * Internal. Used to return an accessible ReflectionMethod on the
	 * proxied object.
	 */
	private function getMethod($reflection, $name)
	{
		if ($reflection->hasMethod($name))
		{
			$method = $reflection->getMethod($name);
		}
		else
		{
			$parent = $reflection;
			while ($parent = $parent->getParentClass())
			{
				if ($parent->hasMethod($name))
				{
					$method = new ReflectionMethod($parent->getName(), $name);
					break;
				}	
			}
			if ($parent === false)
			{
				return null;
			}			
		}
		$method->setAccessible(true);
		return $method;
	}
	
	/**
	 * Magic method to get a property value in the proxied object
	 */
	public function __get($name)
	{
		$prop = $this->getProperty($this->reflection, $name);
		if ($prop === null)
		{
			if ($this->silence === true) return null;
			throw new Exception("Unexisting property " . $this->reflection->getName() . "::\${$name}");
		}
		return $prop->getValue($this->object);
	}

	/**
	 * Magic method to set a property value in the proxied object
	 */
	public function __set($name, $value)
	{
		$prop = $this->getProperty($this->reflection, $name);
		if ($prop === null)
		{
			$this->object->$name = $value;
		}
		else
		{
			$prop->setValue($this->object, $value);
		}
		return $value;
	}

	/**
	 * Magic method to call a method on the proxied object
	 */
	public function __call($name, $arguments)
	{
		$method = $this->getMethod($this->reflection, $name);
		if ($method === null)
		{
			if ($this->silence === true) return null;
			throw new Exception("Unexisting method " . $this->reflection->getName() . "::{$name}");
		}
		return $method->invokeArgs($this->object, $arguments);
	}
	
}
