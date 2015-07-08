<?php
namespace TYPO3\Blog\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Blog".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class SetupController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('foos', array(
			'bar', 'baz'
		));
	}

}