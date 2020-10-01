<?php

namespace App\Util;

use App\Entity\User;

class GamificationEngine
{
    public const EXP_THRESHOLD = 100;

    /**
     * Formule : (Exp Level n) = (Exp Level n-1) + (Level n-1) * 100.
     *
     * Exemple :
     * - Level 1 = 0
     * - Level 2 = 0 + 1 * 100 = 100
     * - Level 3 = 100 + 2 * 100 = 300
     * - Level 4 = 600
     * - etc
     */
    public static function computeLevelForUser(User $user): int
    {
        $level = 1;

        while ($user->getExperience() >= self::computeExperienceNeededForLevel($level + 1)) {
            ++$level;
        }

        return $level;

        // ou plus "simplement" :
        // return (int) (0.5 + sqrt(1 + 8 * $user->getExperience() / self::EXP_THRESHOLD) / 2);
    }

    public static function computeLevelCompletionForUser(User $user): int
    {
        $currentLevelExp = self::computeExperienceNeededForLevel($user->getLevel());
        $nextLevelExp = self::computeExperienceNeededForLevel($user->getLevel() + 1);

        return ($user->getExperience() - $currentLevelExp) / ($nextLevelExp - $currentLevelExp) * 100;
    }

    public static function computeExperienceNeededForLevel(int $level): int
    {
        if (1 === $level) {
            return 0;
        }

        $previousLevel = --$level;

        return self::computeExperienceNeededForLevel($previousLevel) + $previousLevel * self::EXP_THRESHOLD;
    }
}
