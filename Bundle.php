<?php
namespace Asgard\Entityimage;

class Bundle extends \Asgard\Core\BundleLoader {
	public function buildApp($container) {
		$container->register('Asgard.Entity.PropertyType.image', function($container, $params) { return new ImageProperty($params); });
	}

	public function run($container) {
		$container['widgetsManager']->addNamespace('Asgard\Entityimage');
		$container['adminEntityFieldsSolver']->addMultiple(function($property) {
			if($property instanceof \Asgard\Entityimage\ImageProperty)
				return new MultipleImagesField;
			return new \Admin\Libs\Form\DynamicGroup;
		});
		$container['adminEntityFieldsSolver']->add(function($property) {
			if(get_class($property) == 'Asgard\Entityimage\ImageProperty') {
				$field = new \Asgard\Form\Fields\FileField;
				$field->setDefaultWidget('image');
				return $field;
			}
		});
	}
}