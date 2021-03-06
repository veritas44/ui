<?php

declare(strict_types=1);

namespace atk4\ui\demo;

/** @var \atk4\ui\App $app */
require_once __DIR__ . '/../init-app.php';

/** @var \atk4\ui\View $mySwitcherClass */
$mySwitcherClass = get_class(new class() extends \atk4\ui\View {
    protected function init(): void
    {
        parent::init();

        \atk4\ui\Header::addTo($this, ['My name is ' . $this->name, 'red']);

        $buttons = \atk4\ui\View::addTo($this, ['ui' => 'basic buttons']);
        \atk4\ui\Button::addTo($buttons, ['Yellow'])->setAttr('data-id', 'yellow');
        \atk4\ui\Button::addTo($buttons, ['Blue'])->setAttr('data-id', 'blue');
        \atk4\ui\Button::addTo($buttons, ['Button'])->setAttr('data-id', 'button');

        $buttons->on('click', '.button', new \atk4\ui\JsReload($this, [$this->name => (new \atk4\ui\Jquery())->data('id')]));

        switch ($this->getApp()->stickyGet($this->name)) {
            case 'yellow':
                self::addTo(\atk4\ui\View::addTo($this, ['ui' => 'yellow segment']));

                break;
            case 'blue':
                self::addTo(\atk4\ui\View::addTo($this, ['ui' => 'blue segment']));

                break;
            case 'button':
                \atk4\ui\Button::addTo(\atk4\ui\View::addTo($this, ['ui' => 'green segment']), ['Refresh page'])->link([]);

                break;
        }
    }
});

$view = \atk4\ui\View::addTo($app, ['ui' => 'segment']);

$mySwitcherClass::addTo($view);
