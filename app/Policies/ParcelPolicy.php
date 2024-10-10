<?php

namespace App\Policies;

use App\Models\Parcel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParcelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // Svi korisnici mogu vidjeti listu parcela
    }

    public function view(User $user, Parcel $parcel)
    {
        return true; // Svi korisnici mogu vidjeti pojedinačnu parcelu
    }

    public function create(User $user)
    {
        return !$user->hasRole('viewer'); // Svi osim viewera mogu kreirati parcele
    }

    public function update(User $user, Parcel $parcel)
    {
        return !$user->hasRole('viewer'); // Svi osim viewera mogu ažurirati parcele
    }

    public function delete(User $user, Parcel $parcel)
    {
        return $user->hasRole('admin'); // Samo admin može brisati parcele
    }

    public function viewOnly(User $user)
    {
        return $user->hasRole('viewer'); // Provjerava je li korisnik viewer
    }
}