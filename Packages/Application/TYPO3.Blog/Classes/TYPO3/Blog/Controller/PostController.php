<?php
namespace TYPO3\Blog\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Blog".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Blog\Domain\Model\Post;

class PostController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Blog\Domain\Repository\PostRepository
	 */
	protected $postRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('posts', $this->postRepository->findAll());
	}

	/**
	 * @param \TYPO3\Blog\Domain\Model\Post $post
	 * @return void
	 */
	public function showAction(Post $post) {
		$this->view->assign('post', $post);
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \TYPO3\Blog\Domain\Model\Post $newPost
	 * @return void
	 */
	public function createAction(Post $newPost) {

		$this->postRepository->add($newPost);
		$this->addFlashMessage('Created a new post.');
		$this->redirect('index');
	}

	/**
	 * @param \TYPO3\Blog\Domain\Model\Post $post
	 * @return void
	 */
	public function editAction(Post $post) {
		$this->view->assign('post', $post);
	}

	/**
	 * @param \TYPO3\Blog\Domain\Model\Post $post
	 * @return void
	 */
	public function updateAction(Post $post) {
		$this->postRepository->update($post);
		$this->addFlashMessage('Updated the post.');
		$this->redirect('index');
	}

	/**
	 * @param \TYPO3\Blog\Domain\Model\Post $post
	 * @return void
	 */
	public function deleteAction(Post $post) {
		$this->postRepository->remove($post);
		$this->addFlashMessage('Deleted a post.');
		$this->redirect('index');
	}

}