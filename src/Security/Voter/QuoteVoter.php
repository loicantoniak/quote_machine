<?php

namespace App\Security\Voter;

use App\Entity\Quote;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class QuoteVoter extends Voter
{
    const QUOTE_EDIT = 'edit';
    const QUOTE_DELETE = 'delete';
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['edit', 'delete'])
            && $subject instanceof Quote;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var Quote $quote */
        $quote = $subject;

        switch ($attribute) {
            case self::QUOTE_DELETE:
            case self::QUOTE_EDIT:
                return $user === $quote->getAuthor();
        }

        return false;
    }
}
