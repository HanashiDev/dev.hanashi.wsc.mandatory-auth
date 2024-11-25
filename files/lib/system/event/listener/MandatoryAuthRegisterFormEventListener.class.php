<?php

namespace wcf\system\event\listener;

use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;

final class MandatoryAuthRegisterFormEventListener extends AbstractEventListener
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

        if (!WCF::getSession()->getVar('__3rdPartyProvider')) {
            HeaderUtil::redirect(LinkHandler::getInstance()->getControllerLink($className));

            exit;
        }
    }
}
