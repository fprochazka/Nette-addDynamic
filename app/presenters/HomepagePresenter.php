<?php
use Nette\Forms\Container;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	public function actionDefault()
	{
		if ($values = $this->getSession('values')->users) {
			$this['myForm']->setDefaults($values);
		}
	}



	public function renderDefault()
	{
		$this->template->users = $this->getSession('values')->users;
	}



	/**
	 * @return Form
	 */
	protected function createComponentMyForm($name)
	{
		$form = new Form;

		$presenter = $this;
		$invalidateCallback = function () use ($presenter) {
			/** @var \Nette\Application\UI\Presenter $presenter */
			$presenter->invalidateControl('usersForm');
		};

		// jméno, továrnička, výchozí počet
		$replicator = $form->addDynamic('users', function (Container $container) use ($invalidateCallback) {
			$container->currentGroup = $container->form->addGroup('člověk', FALSE);
			$container->addText('name', 'Jméno');
			$container->addText('email', 'Email')->setRequired();

			$container->addSubmit('remove', 'Smazat')
				->addRemoveOnClick($invalidateCallback);

			$container->addSubmit('removeAjax', 'Smazat Ajaxem')
				->setAttribute('class', 'ajax')
				->addRemoveOnClick($invalidateCallback);
		}, 1);

		/** @var \Kdyby\Replicator\Container $replicator */
		$replicator->addSubmit('add', 'Přidat dalšího')
			->addCreateOnClick($invalidateCallback);

		$replicator->addSubmit('addAjax', 'Přidat dalšího Ajaxem')
			->setAttribute('class', 'ajax')
			->addCreateOnClick($invalidateCallback);

		$form->addSubmit('send', 'Zpracovat')
			->onClick[] = callback($this, 'MyFormSubmitted');

		$form->addSubmit('sendAjax', 'Zpracovat Ajaxem')
			->setAttribute('class', 'ajax')
			->onClick[] = callback($this, 'MyFormSubmitted');

		$this[$name] = $form;
		$form->action .= '#snippet--usersForm';
		return $form;
	}



	/**
	 * @param SubmitButton $button
	 */
	public function MyFormSubmitted(SubmitButton $button)
	{
		// jenom naplnění šablony, bez přesměrování
		$this->getSession('values')->users = $button->form->values;
		$this->redirect('this');
	}

}
