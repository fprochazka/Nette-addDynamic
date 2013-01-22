<?php

use Kdyby\Extension\Forms\BootstrapRenderer\BootstrapRenderer;
use Nette\Forms\Container;
use Nette\Application\UI\Form;



/**
 * Homepage presenter.
 */
class TwBootstrapPresenter extends BasePresenter
{

	/**
	 * @return Nette\Application\UI\Form
	 */
	public function createComponentCreateEditNodeForm()
	{
		$form = new Form;
		$form->setRenderer(new BootstrapRenderer());

		$form->addText("title", "Název uzlu");
		$form->addTextArea("description", "HTML Obsah");
		$form->addCheckbox("isFinal", "Poslední uzel");
		$form->addText("priority", "Priorita");

		//container for all nodes
		$nodes = $form->addDynamic("nodes", function (Container $container) {
			$container->addText("title", "Název");
			$container->addTextArea("description", "HTML Obsah");
			$container->addCheckbox("isFinal", "Poslední uzel");
			$container->addText("priority", "Priorita");

			//button for removing the new node
			$container->addSubmit("removeNode", "Odebrat uzel")
				->addRemoveOnClick();
		}, 2);
		/** @var \Kdyby\Replicator\Container $nodes */

		//button for adding a new node
		$nodes->addSubmit("addNode", "Přidat uzel")
			->addCreateOnClick(TRUE);

		$form->addSubmit("save", "Uložit");

		return $form;
	}

}
