<?php

declare(strict_types=1);

namespace atk4\ui\UserAction;

use atk4\core\Factory;
use atk4\data\Model;
use atk4\ui\Exception;
use atk4\ui\Form;
use atk4\ui\Header;

/**
 * BasicExecutor executor will typically fail if supplied arguments are not sufficient.
 *
 * ArgumentFormExecutor will ask user to fill in the blanks
 */
class ArgumentFormExecutor extends BasicExecutor
{
    /**
     * @var Form
     */
    public $form;

    /**
     * Initialization.
     */
    public function initPreview()
    {
        Header::addTo($this, [$this->action->getCaption(), 'subHeader' => $this->description ?: $this->action->getDescription()]);
        $this->form = Form::addTo($this, ['buttonSave' => $this->executorButton]);

        foreach ($this->action->args as $key => $val) {
            if (is_numeric($key)) {
                throw (new Exception('Action arguments must be named'))
                    ->addMoreInfo('args', $this->action->args);
            }

            if ($val instanceof Model) {
                $val = ['model' => $val];
            }

            if (isset($val['model'])) {
                $val['model'] = Factory::factory($val['model']);
                $this->form->addControl($key, [Form\Control\Lookup::class])->setModel($val['model']);
            } else {
                $this->form->addControl($key, null, $val);
            }
        }

        $this->form->onSubmit(function (Form $form) {
            // set arguments from the model
            $this->setArguments($form->model->get());

            return $this->jsExecute();
        });
    }
}
