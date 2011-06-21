<?php

/**
 * My Application
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */

use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;



/**
 * Homepage presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class HomepagePresenter extends BasePresenter
{

	protected function startup()
	{
		parent::startup();
		Kdyby\Forms\Containers\Replicator::register();
	}



	/**
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentMyForm()
	{
		$presenter = $this;
		$form = new Nette\Application\UI\Form;

		// jméno, továrnička, výchozí počet
		$replicator = $form->addDynamic('users', function (Container $container) use ($presenter) {
			$container->currentGroup = $container->form->addGroup('člověk', FALSE);
			$container->addText('name', 'Jméno');
			$container->addText('surname', 'Příjmení');
			$container->addText('email', 'Email')->setRequired();
			$container->addHidden('id');

			$container->addSubmit('remove', 'Smazat')
				->setValidationScope(FALSE)
				->onClick[] = callback($presenter, 'MyFormRemoveElementClicked');
		}, 1);

		$replicator->addSubmit('add', 'Přidat dalšího člověka')
			->setValidationScope(FALSE)
			->onClick[] = callback($this, 'MyFormAddElementClicked');

		$form->addSubmit('send', 'Zpracovat')
			->onClick[] = callback($this, 'MyFormSubmitted');

		return $form;
	}



	/**
	 * @param SubmitButton $button
	 */
	public function MyFormAddElementClicked(SubmitButton $button)
	{
		$users = $button->parent;

		// spočítat, jestli byly vyplněny políčka
		// ignorovat hodnotu tlačítka
		if ($users->countFilledWithout(array('add')) == count($users->containers)) {
			// přidá jeden řádek do containeru
			$button->parent->createOne();
		}
	}



	/**
	 * @param SubmitButton $button
	 */
	public function MyFormRemoveElementClicked(SubmitButton $button)
	{
		$users = $button->parent->parent;

		// je možné využít hidden prvek, pro uložení ID existujícího záznamu
		// a smazat ho i z databáze
		$id = $button->parent['id']->value;

		// second parameter means cleanup groups
		$users->remove($button->parent, TRUE);
	}



	/**
	 * @param SubmitButton $button
	 */
	public function MyFormSubmitted(SubmitButton $button)
	{
		$users = array();
		foreach ($button->form['users']->values as $user) {
			if (!array_filter((array)$user)) {
				continue;
			}

			$users[] = (array)$user;
		}

		// jenom naplnění šablony, bez přesměrování
		$this->template->users = $users;
	}

}
