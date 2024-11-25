<?php

namespace wcf\system\event\listener;

use wcf\system\request\LinkHandler;
use wcf\util\HeaderUtil;

final class MandatoryAuthLoginFormEventListener extends AbstractEventListener
{
    protected function onReadParameters()
    {
        if (MANDATORY_AUTH_PROVIDER === '') {
            return;
        }

        $className = "wcf\\action\\" . MANDATORY_AUTH_PROVIDER . "AuthAction";
        if (!\class_exists("\\" . $className)) {
            return;
        }

        HeaderUtil::redirect(LinkHandler::getInstance()->getControllerLink($className));

        exit;
    }
}
