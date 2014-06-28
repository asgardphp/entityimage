<?php
namespace Asgard\Entityimage;

class ImageProperty extends \Asgard\Entity\Properties\FileProperty {
	protected static $defaultExtensions = ['png', 'jpg', 'jpeg', 'gif'];

	protected function doUnserialize($str) {
		if(!$str || !file_exists($str))
			return null;
		$image = new \Asgard\Entityimage\Image($str);
		$app = $this->definition->getApp();
		if($app->has('kernel') && isset($app['kernel']['webdir']))
			$image->setWebDir($app['kernel']['webdir']);
		if($app->has('request'))
			$image->setUrl($app['request']->url);
		$image->setFormat($this->get('format'));
		$image->setQuality($this->get('quality'));
		$image->setDir($this->get('dir'));
		return $image;
	}

	public function doSet($val) {
		if(is_string($val) && $val !== null)
			$val = new \Asgard\Entityimage\Image($val);
		if(is_object($val)) {
			if($val instanceof \Asgard\Form\HttpFile)
				$val = new \Asgard\Entityimage\Image($val->src(), $val->getName());
			$app = $this->definition->getApp();
			if($app->has('kernel') && isset($app['kernel']['webdir']))
				$val->setWebDir($app['kernel']['webdir']);
			if($app->has('request'))
				$val->setUrl($app['request']->url);
			$val->setFormat($this->get('format'));
			$val->setQuality($this->get('quality'));
			$val->setDir($this->get('dir'));
		}
		return $val;
	}
}