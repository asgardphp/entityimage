<?php
namespace Asgard\Entityimage\Tests;

class EntityimageTest extends \PHPUnit_Framework_TestCase {
	public static function setUpBeforeClass() {
		$container = new \Asgard\Container\Container;
		$container->register('Asgard.Entity.PropertyType.image', function($container, $params) { return new \Asgard\Entityimage\ImageProperty($params); });
		$container['config']          = new \Asgard\Config\Config;
		$container['hooks']           = new \Asgard\Hook\HooksManager($container);
		$container['cache']           = new \Asgard\Cache\NullCache;
		$container['rulesregistry']   = new \Asgard\Validation\RulesRegistry;
		$container['rulesregistry']->registerNamespace('Asgard\File\Rules');
		$container->register('validator', function($container) {
			$validator = new \Asgard\Validation\Validator;
			$validator->setRegistry($container['rulesregistry']);
			return $validator;
		});

		$entitiesManager = $container['entitiesmanager'] = new \Asgard\Entity\EntitiesManager($container);
		$rulesRegistry = new \Asgard\Validation\RulesRegistry;
		$rulesRegistry->registerNamespace('Asgard\File\Rules');
		$entitiesManager->setValidatorFactory(new \Asgard\Validation\ValidatorFactory($rulesRegistry));
		#set the EntitiesManager static instance for activerecord-like entities (e.g. new Article or Article::find())
		\Asgard\Entity\EntitiesManager::setInstance($entitiesManager);
	}

	public function test() {
		copy(__DIR__.'/Fixtures/fixture.jpg', __DIR__.'/Fixtures/image.jpg');
		$ent = new Fixtures\EntityWithImage([
			'name'  => 'Entity',
			'image' => __DIR__.'/Fixtures/image.jpg',
		]);
		$img = $ent->image;

		$this->assertInstanceOf('Asgard\Entityimage\Image', $img);
		$this->assertEquals(IMAGETYPE_JPEG, $img->format());

		$img->rename(__DIR__.'/Fixtures/newname.png');
		$this->assertTrue(file_exists(__DIR__.'/Fixtures/newname.png'));
		$this->assertEquals(IMAGETYPE_PNG, $img->format());
	}
}