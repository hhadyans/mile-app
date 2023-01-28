<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class Kolis extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'koli_data';
    protected $primaryKey = '_id';
    protected $hidden = ['count'];
    protected $fillable = [
        "koli_length",
        "awb_url",
        "koli_chargeable_weight",
        "koli_width",
        "koli_surcharge",
        "koli_height",
        "koli_description",
        "koli_formula_id",
        "connote_id",
        "koli_volume",
        "koli_weight",
        "koli_id",
        "koli_custom_field",
        "koli_code",
        "count"
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'connote_id', 'connote_id');
    }

    public static function saveData($params)
    {
        $count = self::getCount($params);
        $koliCode = $params['connote_code'].".".$count;
        $data = array(
            "koli_length"               => $params['koli_length'],
            "awb_url"                   => $params['awb_url'],
            "koli_chargeable_weight"    => $params['koli_chargeable_weight'],
            "koli_width"                => $params['koli_width'],
            "koli_surcharge"            => $params['koli_surcharge'],
            "koli_height"               => $params['koli_height'],
            "koli_description"          => $params['koli_description'],
            "koli_formula_id"           => $params['koli_formula_id'],
            "connote_id"                => $params['connote_id'],
            "koli_volume"               => $params['koli_volume'],
            "koli_weight"               => $params['koli_weight'],
            "koli_id"                   => self::generateUUID(),
            "koli_custom_field"         => $params['koli_custom_field'],
            "koli_code"                 => $koliCode,
            "count"                     => $count
        );

        return self::create($data);
    }

    public static function updateData($params, $id)
    {
        $koli = self::where('koli_id', $id)->first();
        if ($koli) {
            $data = array(
                "koli_length"               => $params['koli_length'],
                "awb_url"                   => $params['awb_url'],
                "koli_chargeable_weight"    => $params['koli_chargeable_weight'],
                "koli_width"                => $params['koli_width'],
                "koli_surcharge"            => $params['koli_surcharge'],
                "koli_height"               => $params['koli_height'],
                "koli_description"          => $params['koli_description'],
                "koli_formula_id"           => $params['koli_formula_id'],
                "koli_volume"               => $params['koli_volume'],
                "koli_weight"               => $params['koli_weight'],
                "koli_custom_field"         => $params['koli_custom_field'],
            );

            $koli->update($data);
            
            return $koli;
        }
    }

    private static function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }

    private static function getCount($params)
    {
        $count = 1;
        $data = self::where('connote_id', $params['connote_id'])->latest()->first();
        if ($data) {
            $count = $data->count + 1;
        }
        return $count;
    }
}
