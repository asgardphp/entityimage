<?php
namespace Asgard\Entityimage\Tests;

class EntityimageTest extends \PHPUnit_Framework_TestCase {
	public static function setUpBeforeClass() {
		$app = new \Asgard\Container\Container;
		$app->register('Asgard.Entity.PropertyType.image', function($app, $params) { return new \Asgard\Entityimage\ImageProperty($params); });
		$app['config'] = new \Asgard\Config\Config;
		$app['hooks'] = new \Asgard\Hook\HooksManager($app);
		$app['cache'] = new \Asgard\Cache\NullCache;
		$app['rulesregistry'] = \Asgard\Validation\RulesRegistry::getInstance();
		$app['rulesregistry']->registerNamespace('Asgard\File\Rules');
		$app['entitiesmanager'] = new \Asgard\Entity\EntitiesManager($app);
		\Asgard\Entity\Entity::setApp($app);
	}

	public function test() {
		copy(__DIR__.'/Fixtures/fixture.jpg', __DIR__.'/Fixtures/image.jpg');
		$ent = new Fixtures\EntityWithImage([
			'name' => 'Entity',
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