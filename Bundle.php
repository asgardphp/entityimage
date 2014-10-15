<?php
namespace Asgard\Entityimage;

class Bundle extends \Asgard\Core\BundleLoader {
	public function buildContainer(\Asgard\Container\ContainerInterface $container) {
		$container->register('Asgard.Entity.PropertyType.image', function($container, $params) { return new ImageProperty($params); });
	}

	public function run(\Asgard\Container\ContainerInterface $container) {
		parent::run($container);

		$container['WidgetManager']->addNamespace('Asgard\Entityimage');
		if(isset($container['adminEntityFieldSolver'])) {
			$container['adminEntityFieldSolver']->addMany(function($property) {
				if($property instanceof \Asgard\Entityimage\ImageProperty) {
					if($property->get('web'))
						return new MultipleImagesField;
					else
						return new \Admin\Libs\Form\Fields\MultipleFilesField;
				}
			});

			$container['adminEntityFieldSolver']->add(function($property) {
				if(get_class($property) == 'Asgard\Entityimage\ImageProperty') {
					$field = new \Asgard\Form\Fields\FileField;
					if($property->get('web'))
						$field->setDefaultWidget('image');
					return $field;
				}
			});
		}
	}

	protected function loadControllers() {
		if(!class_exists('Admin\Libs\Controller\AdminParentController'))
			return [];
		return parent::loadControllers();
	}
}