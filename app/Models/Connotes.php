<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class Connotes extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'connotes';
    protected $primaryKey = 'connote_id';
    protected $fillable = [
        "connote_id",
        "connote_number",
        "connote_service",
        "connote_service_price",
        "connote_amount",
        "connote_code",
        "connote_booking_code",
        "connote_order",
        "connote_state",
        "connote_state_id",
        "zone_code_from",
        "zone_code_to",
        "surcharge_amount",
        "transaction_id",
        "actual_weight",
        "volume_weight",
        "chargeable_weight",
        "created_at",
        "updated_at",
        "organization_id",
        "location_id",
        "connote_total_package",
        "connote_surcharge_amount",
        "connote_sla_day",
        "location_name",
        "location_type",
        "source_tariff_db",
        "id_source_tariff",
        "pod",
        "history"
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'connote_id', 'connote_id');
    }

    public static function saveData($params)
    {
        $data = array(
            "connote_id"                => $params['connote_id'],
            "connote_number"            => $params['connote_number'],
            "connote_service"           => $params['service'],
            "connote_service_price"     => $params['transaction_amount'],
            "connote_amount"            => $params['transaction_amount'],
            "connote_code"              => $params['connote_code'],
            "connote_booking_code"      => $params['connote_booking_code'],
            "connote_order"             => $params['connote_order'],
            "connote_state"             => $params['transaction_state'],
            "connote_state_id"          => $params['transaction_state_id'],
            "zone_code_from"            => $params['origin_data'][0]['zone_code'],
            "zone_code_to"              => $params['destination_data'][0]['zone_code'],
            "surcharge_amount"          => 0,
            "transaction_id"            => $params['transaction_id'],
            "actual_weight"             => $params['actual_weight'],
            "volume_weight"             => $params['volume_weight'],
            "chargeable_weight"         => $params['chargeable_weight'],
            "created_at"                => $params['created_at'],
            "updated_at"                => $params['updated_at'],
            "organization_id"           => $params['organization_id'],
            "location_id"               => $params['location_id'],
            "connote_total_package"     => 0,
            "connote_surcharge_amount"  => 0,
            "connote_sla_day"           => $params['sla_day'],
            "location_name"             => $params['location_name'],
            "location_type"             => $params['location_connote_type'],
            "source_tariff_db"          => $params['source_tariff_db'],
            "id_source_tariff"          => $params['id_source_tariff'],
            "pod"                       => $params['pod'],
            "history"                   => $params['history']
        );

        return self::create($data);
    }

    public static function updateData($params, $id)
    {
        $connote = self::where('connote_id', $id)->first();
        if ($connote) {
            $data = array(
                "connote_number"            => $params['connote_number'] ?? $connote->connote_number,
                "connote_service"           => $params['service'] ?? $connote->connote_service,
                "connote_service_price"     => $params['transaction_amount'] ?? $connote->connote_service_price,
                "connote_amount"            => $params['transaction_amount'] ?? $connote->connote_amount,
                "connote_booking_code"      => $params['connote_booking_code'] ?? $connote->connote_booking_code,
                "connote_order"             => $params['connote_order'] ?? $connote->connote_order,
                "connote_state"             => $params['transaction_state'] ?? $connote->connote_state,
                "connote_state_id"          => $params['transaction_state_id'] ?? $connote->connote_state_id,
                "zone_code_from"            => $params['origin_data'][0]['zone_code'] ?? $connote->zone_code_from,
                "zone_code_to"              => $params['destination_data'][0]['zone_code'] ?? $connote->zone_code_to,
                "actual_weight"             => $params['actual_weight'] ?? $connote->actual_weight,
                "volume_weight"             => $params['volume_weight'] ?? $connote->volume_weight,
                "chargeable_weight"         => $params['chargeable_weight'] ?? $connote->chargeable_weight,
                "organization_id"           => $params['organization_id'] ?? $connote->organization_id,
                "connote_sla_day"           => $params['sla_day'] ?? $connote->connote_sla_day,
                "location_name"             => $params['location_name'] ?? $connote->location_name,
                "location_type"             => $params['location_connote_type'] ?? $connote->location_type,
                "source_tariff_db"          => $params['source_tariff_db'] ?? $connote->source_tariff_db,
                "id_source_tariff"          => $params['id_source_tariff'] ?? $connote->id_source_tariff,
                "pod"                       => $params['pod'] ?? $connote->pod,
                "history"                   => $params['history'] ?? $connote->history
            );

            $connote->update($data);
            return $connote;
        }

    }

    public static function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }

    public static function generateConnoteCode($params)
    {
        $code = "AWB" . str_pad($params['connote_number'], 3, '0', STR_PAD_LEFT). str_pad($params['transaction_state_id'], 3, '0', STR_PAD_LEFT) . Carbon::now()->format("Ymd");
        return $code;
    }

    public static function getConnoteOrder()
    {
        return self::count();
    }
}
