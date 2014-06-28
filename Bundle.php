<?php
namespace Asgard\Entityimage;

class Bundle extends \Asgard\Core\BundleLoader {
	public function buildApp($app) {
		$app->register('Asgard.Entity.PropertyType.image', function($app, $params) { return new ImageProperty($params); });
	}

	public function run($app) {
		$app['adminEntityFieldsSolver']->add(function($property) {
			if(get_class($property) == 'Asgard\Entityimage\ImageProperty') {
				$field = new \Asgard\Form\Fields\FileField;
				$field->setDefaultWidget('image');
				return $field;
			}
		});
	}
}