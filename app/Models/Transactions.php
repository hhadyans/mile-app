<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class Transactions extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'transactions';
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        "transaction_id",
        "customer_name",
        "customer_code",
        "transaction_amount",
        "transaction_discount",
        "transaction_additional_field",
        "transaction_payment_type",
        "transaction_state",
        "transaction_code",
        "transaction_order",
        "location_id",
        "organization_id",
        "created_at",
        "updated_at",
        "transaction_payment_type_name",
        "transaction_cash_amount",
        "transaction_cash_change",
        "connote_id",
        "customer_attribute",
        "custom_field",
        "currentLocation"
    ];

    public function connote()
    {
        return $this->hasOne(Connotes::class, 'connote_id', 'connote_id');
    }

    public function destination()
    {
        return $this->hasOne(DestinationData::class, 'location_id', 'location_id');
    }

    public function origin()
    {
        return $this->hasOne(OriginData::class, 'location_id', 'location_id');
    }

    public function koli()
    {
        return $this->hasMany(Kolis::class, 'connote_id', 'connote_id');
    }

    public static function saveData($params)
    {
        $data = array(
            "transaction_id"                => $params['transaction_id'],
            "customer_name"                 => $params['customer_name'],
            "customer_code"                 => $params['customer_code'],
            "transaction_amount"            => $params['transaction_amount'],
            "transaction_discount"          => $params['transaction_discount'],
            "transaction_additional_field"  => $params['transaction_additional_field'],
            "transaction_payment_type"      => $params['transaction_payment_type'],
            "transaction_state"             => $params['transaction_state'],
            "transaction_code"              => $params['transaction_code'],
            "transaction_order"             => $params['transaction_order'],
            "location_id"                   => $params['location_id'],
            "organization_id"               => $params['organization_id'],
            "transaction_payment_type_name" => $params['payment_type'],
            "transaction_cash_amount"       => $params['transaction_cash_amount'],
            "transaction_cash_change"       => $params['transaction_cash_change'],
            "connote_id"                    => $params['connote_id'],
            "customer_attribute"            => array(
                "Nama_Sales"                => $params['customer_attribute'][0]['nama_sales'],
                "TOP"                       => $params['customer_attribute'][0]['top'],
                "Jenis_Pelanggan"           => $params['customer_attribute'][0]['jenis_pelanggan']
            ),
            "custom_field"                  => $params['custom_field'][0],
            "currentLocation"               => array(
                "name"  => $params['location_name'],
                "code"  => $params['location_code'],
                "type"  => $params['location_type']
            )
        );

        return self::create($data);
    }

    public static function updateData($params, $id)
    {
        $transaction = self::where('transaction_id', $id)->first();
        if ($transaction) {
            $data = array(
                "customer_name"                 => $params['customer_name'] ?? $transaction->customer_name,
                "customer_code"                 => $params['customer_code'] ?? $transaction->customer_code,
                "transaction_amount"            => $params['transaction_amount'] ?? $transaction->transaction_amount,
                "transaction_discount"          => $params['transaction_discount'] ?? $transaction->transaction_discount,
                "transaction_additional_field"  => $params['transaction_additional_field'] ?? $transaction->transaction_additional_field,
                "transaction_payment_type"      => $params['transaction_payment_type'] ?? $transaction->transaction_payment_type,
                "transaction_state"             => $params['transaction_state'] ?? $transaction->transaction_state,
                "transaction_order"             => $params['transaction_order'] ?? $transaction->transaction_order,
                "organization_id"               => $params['organization_id'] ?? $transaction->organization_id,
                "transaction_payment_type_name" => $params['payment_type'] ?? $transaction->transaction_payment_type_name,
                "transaction_cash_amount"       => $params['transaction_cash_amount'] ?? $transaction->transaction_cash_amount,
                "transaction_cash_change"       => $params['transaction_cash_change'] ?? $transaction->transaction_cash_change,
                "customer_attribute"            => array(
                    "Nama_Sales"                => $params['customer_attribute'][0]['nama_sales'] ?? $transaction->Nama_Sales,
                    "TOP"                       => $params['customer_attribute'][0]['top'] ?? $transaction->TOP,
                    "Jenis_Pelanggan"           => $params['customer_attribute'][0]['jenis_pelanggan'] ?? $transaction->Jenis_Pelanggan
                ),
                "custom_field"                  => $params['custom_field'][0] ?? $transaction->custom_field,
                "currentLocation"               => array(
                    "name"  => $params['location_name'] ?? $transaction->name,
                    "code"  => $params['location_code'] ?? $transaction->code,
                    "type"  => $params['location_type'] ?? $transaction->type
                )
            );

            $transaction->update($data);
            
            return $transaction;
        }
    }

    public static function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }

    public static function generateTransactionCode($params, $order)
    {
        $code = $params['origin_data'][0]['zone_code'] . Carbon::now()->format("Ymd") . str_pad($order, 3, '0', STR_PAD_LEFT);
        return $code;
    }

    public static function getCountOrder()
    {
        return self::count();
    }
}
