<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatisticsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Policy on View daily report
     * @param User $user
     * @return bool
     */
    public function viewDailyReport(User $user)
    {
        return $user->hasPermission('ViewDailyReport');
    }

    /**
     * Policy on View card history
     * @param User $user
     * @return bool
     */
    public function viewCardHistory(User $user)
    {
        return $user->hasPermission('ViewCardHistory');
    }

    /**
     * Policy on exporting card history
     * @param User $user
     * @return bool
     */
    public function exportCardHistory(User $user)
    {
        return $user->hasPermission('ExportCardHistory');
    }

    /**
     * Policy on View pay history
     * @param User $user
     * @return bool
     */
    public function viewPayHistory(User $user)
    {
        return $user->hasPermission('ViewPayHistory');
    }

    /**
     * Policy on exporting card history
     * @param User $user
     * @return bool
     */
    public function exportPayHistory(User $user)
    {
        return $user->hasPermission('ExportPayHistory');
    }

    /**
     * Policy on View pay history
     * @param User $user
     * @return bool
     */
    public function viewIAPHistory(User $user)
    {
        return $user->hasPermission('ViewIAPHistory');
    }

    /**
     * Policy on exporting card history
     * @param User $user
     * @return bool
     */
    public function exportIAPHistory(User $user)
    {
        return $user->hasPermission('ExportIAPHistory');
    }
}
