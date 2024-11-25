<?php

namespace wcf\system\option;

use Override;
use wcf\action\AbstractOauth2Action;
use wcf\action\AbstractOauth2AuthAction;
use wcf\data\option\Option;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

final class MandatoryAuthOptionType extends AbstractOptionType
{
    /**
     * @var string[]
     */
    private array $authNames;

    #[Override]
    public function getFormElement(Option $option, $value)
    {
        return WCF::getTPL()->fetch('mandatoryAuthOptionType', 'wcf', [
            'option' => $option,
            'value' => $value,
            'authNames' => $this->getAuthNames(),
        ], true);
    }

    #[Override]
    public function validate(Option $option, $newValue)
    {
        if ($newValue !== '' && !\in_array($newValue, $this->getAuthNames())) {
            throw new UserInputException($option->optionName);
        }
    }

    /**
     * @return string[]
     */
    private function getAuthNames(): array
    {
        if (!isset($this->authNames)) {
            $actionDir = WCF_DIR . 'lib/action/';
            $files = \scandir($actionDir);
            $authFiles = \array_filter(
                $files,
                static fn ($file) => \str_ends_with($file, 'AuthAction.class.php')
                                    && !\str_starts_with($file, 'Abstract')
            );

            $authNames = [];
            foreach ($authFiles as $authFile) {
                $className = "\\wcf\\action\\" . \substr($authFile, 0, -10);
                if (!\class_exists($className)) {
                    continue;
                }

                $class = new $className();
                if (!($class instanceof AbstractOauth2AuthAction || $class instanceof AbstractOauth2Action)) {
                    continue;
                }

                $authNames[] = \substr($authFile, 0, -20);
            }

            $this->authNames = $authNames;
        }

        return $this->authNames;
    }
}
