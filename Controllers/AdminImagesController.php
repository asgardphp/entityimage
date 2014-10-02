<?php
namespace Asgard\Entityimage\Controllers;

/**
 * @Prefix("admin/files/:entityAlias/:id/:file")
 */
class AdminImagesController extends \Admin\Libs\Controller\AdminParentController {
	public function before(\Asgard\Http\Request $request) {
		$this->layout = false;
		$entityAlias = $request['entityAlias'];
		$entityClass = $this->container['adminManager']->getClass($entityAlias);
		if(!($this->entity = $entityClass::load($request['id'])))
			$this->forward404();
		if(!$this->entity->hasProperty($request['file']))
			$this->forward404();

		return parent::before($request);
	}

	/**
	 * @Route("addimage")
	 */
	public function addimageAction($request) {
		$entity = $this->entity;
		$container = $this->container;

		if(!$request->file->has('Filedata'))
			return $this->response->setCode(400)->setContent($this->container['translator']->trans('An error occured.'));

		try {
			$postFile = $request->file['Filedata'];

			$fileName = $request['file'];
			$property = $entity->property($fileName);

			$postFile = $entity->get($fileName)->add($postFile);
			$entity->save();

			$deleteurl = $container['resolver']->url(['Admin\Controllers\FilesController', 'deleteOne'], ['entityAlias' => $request['entityAlias'], 'id' => $entity->id, 'pos' => $entity->get($fileName)->size(), 'file' => $request['file']]);
			$downloadurl = $container['resolver']->url(['Admin\Controllers\FilesController', 'downloadOne'], ['entityAlias' => $request['entityAlias'], 'id' => $entity->id, 'pos' => $entity->get($fileName)->size(), 'file' => $request['file']]);
			$thumb_url = $request->url->to('imagecache/admin_thumb/'.$postFile->srcFromWebDir());
			$url = $postFile->url();
			$response = '<li>
						<img src="'.$thumb_url.'" alt=""/>
						<ul>
							<li class="view"><a href="'.$url.'" rel="facebox">'.$this->container['translator']->trans('See').'</a></li>
							<li class="delete"><a href="'.$deleteurl.'">'.$this->container['translator']->trans('Del.').'</a></li>
						</ul>
					</li>
					<script>
					$(\'a[rel*=facebox]\').facebox()
					</script>';
		} catch(\Asgard\Orm\EntityException $e) {
			return $this->response->setCode(400)->setContent($this->container['translator']->trans('An error occured.'));
		}

		return $this->response->setCode(200)->setContent($response);
	}
}