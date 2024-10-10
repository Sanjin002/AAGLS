<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'acKey',
        'ClientNumber',
        'ClientReference',
        'Content',
        'Count',
        'CODAmount',
        'DeliveryCity',
        'DeliveryContactName',
        'DeliveryContactPhone',
        'DeliveryCountryIsoCode',
        'DeliveryName',
        'DeliveryStreet',
        'DeliveryZipCode',
        'gls_parcel_id', 
        'status',
        'gls_response',
        'label_expiry',
        'user_id',
        'department_id'
    ];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}