<?php

namespace App\Services;

use App\Models\UserType;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    /**
     * Get the current active profile from session.
     */
    public function getActiveProfile()
    {
        if (!Session::has('active_profile')) {
            $this->initActiveProfile();
        }

        return Session::get('active_profile');
    }

    /**
     * Initialize the active profile based on user's default type.
     */
    public function initActiveProfile()
    {
        $user = Auth::user();
        if (!$user) return null;

        $profile = UserType::find($user->user_type_id);
        if ($profile) {
            $this->setActiveProfile($profile);
        }
        
        return $profile;
    }

    /**
     * Set the active profile in session.
     */
    public function setActiveProfile(UserType $profile)
    {
        Session::put('active_profile', $profile);
        Session::put('active_profile_id', $profile->id);
        Session::put('active_profile_slug', $profile->slug);
    }

    /**
     * Switch to a new profile if the user has permission.
     */
    public function switchProfile($profileId)
    {
        $user = Auth::user();
        if (!$user) return false;

        // Admin can switch to any profile
        if ($user->isAdmin()) {
            $profile = UserType::find($profileId);
        } else {
            $profile = $user->profiles()->where('user_types.id', $profileId)->first();
        }

        if ($profile) {
            $this->setActiveProfile($profile);
            return $profile;
        }

        return false;
    }
}
