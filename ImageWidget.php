<?php
namespace Asgard\Entityimage;

class ImageWidget extends \Asgard\Form\Widget {
	public function render(array $options=[]) {
		$options = $this->options+$options;

		$attrs = [];
		if(isset($options['attrs']))
			$attrs = $options['attrs'];

		$str = \Asgard\Form\HTMLHelper::tag('input', [
			'type'	=>	'file',
			'name'	=>	$this->name,
			'id'	=>	isset($options['id']) ? $options['id']:null,
		]+$attrs);
		$container = \Asgard\Container\Container::singleton();
		$entity = $this->field->getTopForm()->getEntity();
		$name = $this->field->getName();
		$optional = !$entity->getDefinition()->property($name)->required();

		if($entity->isOld() && $entity->$name && $entity->$name->exists()) {
			$file = $entity->$name;
			if(!$file->src())
				return $str;
			$str .= '<p>
				<a target="_blank" href="'.$container['httpKernel']->getRequest()->url->to($file->srcFromWebDir()).'" rel="facebox"><img src="'.$container['imagecache']->url($file->srcFromWebDir(), 'admin_thumb').'" alt=""/></a>
			</p>';

			if($optional)
				$str .= '<a href="'.$container['resolver']->url(['Admin\Controllers\FilesController', 'delete'], ['entityAlias' => $container['adminManager']->getAlias(get_class($entity)), 'id' => $entity->id, 'file' => $name]).'">'. $container['translator']->trans('Delete').'</a><br/><br/>';
		}

		return $str;
	}
}