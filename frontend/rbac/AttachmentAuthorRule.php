<?php

namespace frontend\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Checks if user_id matches user passed via params
 */
class AttachmentAuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['attachment']) ? $params['attachment']->user_id == $user : false;
    }
}
