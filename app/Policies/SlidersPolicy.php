<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Sliders;
use Illuminate\Auth\Access\HandlesAuthorization;

class SlidersPolicy
{
    use HandlesAuthorization;

    /**
     * Model instance
     */
    private $slidersModel;

    /**
     * GamesPolicy constructor.
     * @param Sliders $slidersModel
     */
    public function __construct(Sliders $slidersModel)
    {
        // create model instance
        $this->slidersModel = $slidersModel;
    }

    /**
     * View slider permissions
     * @param User $user
     * @return bool
     */
    public function viewSliders(User $user)
    {
        return $user->hasPermission('ViewSliders');
    }

    /**
     * Create slider permission
     * @param User $user
     * @return bool
     */
    public function createSlider(User $user)
    {
        return $user->hasPermission('CreateSlider');
    }

    /**
     * Update slider permission
     * @param User $user
     * @return bool
     */
    public function updateSlider(User $user)
    {
        return $user->hasPermission('UpdateSlider');
    }

    /**
     * Delete slider permission
     * @param User $user
     * @return bool
     */
    public function deleteSlider(User $user)
    {
        // get current slider
        $currentSlider = $this->slidersModel->getSliderInfo(request()->sliderId);

        // check is_default property
        $isDefault = $currentSlider->is_default;

        return $user->hasPermission('DeleteSlider')
            && !$isDefault;
    }
}
