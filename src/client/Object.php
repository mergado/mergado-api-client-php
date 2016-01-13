<?php

namespace Mergado\ApiClient;

class Object {

	/**
	 * This method is being used whilst trying to read from any undeclared
	 * or inaccessible property. It then checks for the existence of any
	 * get<variableName>() method and invokes it.
	 *
	 * @param string Name of the property
	 * @throws \LogicException
	 */
	public function __get(string $name) {

		$getterMethod = 'get'. $name;
		if (method_exists($this, $getterMethod)) {
			return $this->{$getterMethod}();
		}

		throw new \LogicException(sprintf(
			"Cannot read an undeclared property '%s' in %s.",
			$name,
			get_called_class()
		));

	}

	/**
	 * This method is being used whilst trying to write to any undeclared
	 * or inaccessible property. It then checks for the existence of any
	 * set<variableName>() method and invokes it.
	 *
	 * If such method is not found, throw an exception
	 *
	 * @param string Name of the property
	 * @param mixed Value to be written
	 * @throws \LogicException
	 */
	public function __set(string $name, $value) {

		$setterMethod = 'set'. $name;
		if (method_exists($this, $setterMethod)) {
			$this->{$setterMethod}($value);
		} else {
			throw new \LogicException(sprintf(
				"Cannot write to an undeclared property '%s' in %s",
				$name,
				get_called_class()
			));
		}

	}

}
