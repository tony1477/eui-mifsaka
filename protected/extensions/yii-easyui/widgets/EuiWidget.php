<?php

abstract class EuiWidget extends CWidget {

	/**
	 * @var array the properties that are not rendered
	 */
	private $_invalidOptions = array();

	/**
	 * @var array attributes of html tags
	 */
	public $htmlOptions = array();

	/**
	 * @var string Add a custom specification style to the component
	 */
	public $style;

	/**
	 * @var string Add a custom specification style to the component
	 */
	public $cssClass;


	protected function encodeOptions($value)
	{
		$es = array();
		foreach ($value as $i => $v)
		{
			if (is_bool($v))
				$v = $v? 'true' : 'false';
			else if (is_array($v))
				$v = !empty($v) ? CJavaScript::encode($v) : null;
			else if (is_string($v))
			{
				if (strpos($v, 'js:') === 0)
					$v = substr($v, 3);
				else
					$v = "'".$v."'";
			}

			if ($v !== null)
				$es[] = $i.':'.$v;
		}

		if (!empty($es))
			return implode(',', $es);
		else
			return null;
	}

	/**
	 * Return if property is ignored
	 * @param string $propName the property name
	 * @return bool
	 */
	private function isInvalidOption($optionName)
	{
		foreach ($this->_invalidOptions as $opt)
		{
			if ($opt === $optionName)
				return true;
		}
		return false;
	}

	/**
	 * Add property of self class to ignored list
	 * @param mixed $props property name or arry of property name
	 */
	protected function addInvalidOptions($options)
	{
		if (is_array($options))
			$this->_invalidOptions = array_merge($this->_invalidOptions, $options);
		else
			$this->_invalidOptions[] = $options;
	}

	/**
	 * Check of item class name is valid
	 * @param array $config represents configuration object
	 * @param string $itemClassName name of class item
	 * @throws CException
	 */
	protected function prepareConfig(array $config, $itemClassName=null)
	{
		if (!isset($config['class']) && $itemClassName === null)
			throw new CException(Yii::t('easyui','Object configuration not definied "class" value.'));

		if (!isset($config['class']))
			$config['class'] = $itemClassName;

		if ($itemClassName !== null)
		{
			if ($config['class'] !== $itemClassName)
				throw new CException(Yii::t('easyui','Object configuration contain "class" with wrong value.'));
		}

		return $config;
	}


	/**
	 * Return a new collection with elements configuration substuidos of objects
	 * @param array $collection The elements configuration
	 * @param string $itemClassName The class name of items
	 * @param string $prefix The prefix of generation item name
	 * @return array
	 */
	protected function initCollection(array $collection, $itemClassName=null, $prefix='_i')
	{
		$items = array();
		foreach($collection as $i => $item)
		{
			$item = $this->prepareConfig($item, $itemClassName);
			$item = Yii::createComponent($item, $this);

			$items[$i]= $item;
			$item->init();
		}
		return $items;
	}

	/**
	 * @return string the CSS class that will be added to this component's Element
	 */
	protected function getCssClass()
	{
		return $this->cssClass;
	}

	/**
	 * @return array Represents configuration this component
	 */
	public function toArray()
	{
		$props = array();

		foreach ($this as $key => $value)
		{
			if ($value === null || is_object($value))
				continue;

			if (!$this->isInvalidOption($key))
				$props[$key] = $value;
		}

		unset($props['skin']);
		unset($props['cssClass']);
		unset($props['htmlOptions']);
		unset($props['_invalidOptions']);

		return $props;
	}

	/**
	 * @return array Represents options this component
	 */
	public function toOptions()
	{
		$options = array();
		$props = $this->toArray();

		if ($this->getCssClass())
			$options['class'] = $this->getCssClass();

		if (isset($props['style']))
		{
			$options['style'] = $props['style'];
			unset($props['style']);
		}

		$options['id'] = $this->getId();
		$options['data-options'] = $this->encodeOptions($props);
		$options = array_merge($options, $this->htmlOptions);
		return $options;
	}
}

?>
