<?php

namespace srag\CustomInputGUIs\DhbwTraining\Loader;

use Closure;
use ILIAS\UI\Component\Component;
use ILIAS\UI\Implementation\DefaultRenderer;
use ILIAS\UI\Implementation\Render\ComponentRenderer;
use ILIAS\UI\Implementation\Render\Loader;
use ILIAS\UI\Renderer;
use Pimple\Container;
use srag\CustomInputGUIs\DhbwTraining\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\DhbwTraining\InputGUIWrapperUIInputComponent\Renderer as InputGUIWrapperUIInputComponentRenderer;
use srag\DIC\DhbwTraining\Loader\AbstractLoaderDetector;

/**
 * Class CustomInputGUIsLoaderDetector
 *
 * @package srag\CustomInputGUIs\DhbwTraining\Loader
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CustomInputGUIsLoaderDetector extends AbstractLoaderDetector
{

    /**
     * @return callable
     */
    public static function exchangeUIRendererAfterInitialization() : callable
    {
        $previous_renderer = Closure::bind(function () : callable {
            return $this->raw("ui.renderer");
        }, self::dic()->dic(), Container::class)();

        return function () use ($previous_renderer) : Renderer {
            $previous_renderer = $previous_renderer(self::dic()->dic());

            if ($previous_renderer instanceof DefaultRenderer) {
                $previous_renderer_loader = Closure::bind(function () : Loader {
                    return $this->component_renderer_loader;
                }, $previous_renderer, DefaultRenderer::class)();
            } else {
                $previous_renderer_loader = null; // TODO:
            }

            return new DefaultRenderer(new self($previous_renderer_loader));
        };
    }


    /**
     * @inheritDoc
     */
    public function getRendererFor(Component $component, array $contexts) : ComponentRenderer
    {
        if ($component instanceof InputGUIWrapperUIInputComponent) {
            if (self::version()->is6()) {
                return new InputGUIWrapperUIInputComponentRenderer(self::dic()->ui()->factory(), self::dic()->templateFactory(), self::dic()->language(), self::dic()->javaScriptBinding(),
                    self::dic()->refinery());
            } else {
                return new InputGUIWrapperUIInputComponentRenderer(self::dic()->ui()->factory(), self::dic()->templateFactory(), self::dic()->language(), self::dic()->javaScriptBinding());
            }
        }

        return parent::getRendererFor($component, $contexts);
    }
}
