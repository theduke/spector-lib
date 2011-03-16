<?php

namespace Spector\Writer;

abstract class BaseWriter
{
	public function fromArray(array $arr)
	{
		foreach ($arr as $property => $value)
		{
			$setter = "set" . ucfirst($property);
			if (method_exists($this, $setter))
			{
				call_user_func(array($this, $setter), $value);
			} else if (property_exists($this, $property)) {
				$this->$property = $value;
			}
		}
	}
}