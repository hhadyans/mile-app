<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class OriginData extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'origin_data';
    protected $primaryKey = 'location_id';
    protected $fillable = [
        "customer_name",
        "customer_address",
        "customer_email",
        "customer_phone",
        "customer_address_detail",
        "customer_zip_code",
        "zone_code",
        "organization_id",
        "location_id"
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'location_id', 'location_id');
    }

    public static function saveData($params)
    {
        $data = array(
            "customer_name"             => $params['origin_data'][0]['customer_name'],
            "customer_address"          => $params['origin_data'][0]['customer_address'],
            "customer_email"            => $params['origin_data'][0]['customer_email'],
            "customer_phone"            => $params['origin_data'][0]['customer_phone'],
            "customer_address_detail"   => $params['origin_data'][0]['customer_address_detail'],
            "customer_zip_code"         => $params['origin_data'][0]['customer_zip_code'],
            "zone_code"                 => $params['origin_data'][0]['zone_code'],
            "organization_id"           => $params['organization_id'],
            "location_id"               => $params['location_id'],
        );

        self::create($data);
    }

    public static function updateData($params, $id)
    {
        $origin = self::where('location_id', $id)->first();
        if ($origin) {
            $data = array(
                "customer_name"             => $params['origin_data'][0]['customer_name'],
                "customer_address"          => $params['origin_data'][0]['customer_address'],
                "customer_email"            => $params['origin_data'][0]['customer_email'],
                "customer_phone"            => $params['origin_data'][0]['customer_phone'],
                "customer_address_detail"   => $params['origin_data'][0]['customer_address_detail'],
                "customer_zip_code"         => $params['origin_data'][0]['customer_zip_code'],
                "zone_code"                 => $params['origin_data'][0]['zone_code'],
                "organization_id"           => $params['organization_id'],
            );

            $origin->update($data);

            return $origin;
        }
    }
}
