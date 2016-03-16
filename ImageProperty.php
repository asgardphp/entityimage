<?php
namespace Asgard\Entityimage;

class ImageProperty extends \Asgard\Entity\Property\FileProperty {
	protected static $defaultExtensions = ['png', 'jpg', 'jpeg', 'gif'];

	protected function doUnserialize($str) {
		if(!$str || !file_exists($str))
			return null;
		$image = new \Asgard\Entityimage\Image($str);
		$container = $this->definition->getContainer();
		if($container->has('config') && isset($container['config']['webdir']))
			$image->setWebDir($container['config']['webdir']);
		if($container->has('httpKernel'))
			$image->setUrl($container['httpKernel']->getRequest()->url);
		$image->setFormat($this->get('format'));
		$image->setQuality($this->get('quality'));
		$image->setDir($this->get('dir'));
		$image->setWeb($this->get('web'));
		return $image;
	}

	public function doSet($val, \Asgard\Entity\Entity $entity, $name) {
		if(is_string($val) && $val !== null)
			$val = new \Asgard\Entityimage\Image($val);
		if(is_object($val)) {
			if($val instanceof \Asgard\Http\HttpFile)
				$val = new \Asgard\Entityimage\Image($val->src(), $val->getName());
			$container = $this->definition->getContainer();
			if($container->has('config') && isset($container['config']['webdir']))
				$val->setWebDir($container['config']['webdir']);
			if($container->has('httpKernel'))
				$val->setUrl($container['httpKernel']->getRequest()->url);
			$val->setFormat($this->get('format'));
			$val->setQuality($this->get('quality'));
			$val->setDir($this->get('dir'));
			$val->setWeb($this->get('web'));
		}
		return $val;
	}
}