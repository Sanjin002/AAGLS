<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    protected $fillable = [
        'name', 'gls_account', 'customer_id', 'department_password', 'doc_type_id', 
        'pickup_city', 'pickup_street', 'pickup_zip', 'pickup_country',
        'pickup_contact_name', 'pickup_contact_phone', 'pickup_contact_email'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getClientNumber(): string
    {
        return $this->customer_id; // Pretpostavljamo da je ovo polje koje sadrži GLS ClientNumber
    }

    public function hasGLSCredentials(): bool
    {
        return $this->gls_account && $this->customer_id && $this->department_password;
    }

    public function docType(): BelongsTo
    {
        return $this->belongsTo(DocType::class);
    }
    public function docTypes()
    {
        return $this->belongsToMany(DocType::class);
    }
}