<?php

namespace App\Repositories;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\Transactions;
use App\Models\Connotes;
use App\Models\DestinationData;
use App\Models\OriginData;
use App\Models\Kolis;
use App\Http\Resources\PackagesResource;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Class PackageRepository.
 */
class PackageRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Transactions::class;
    }

    public static function getData()
    {
        $limit = request('limit') ?? 10;
        $data = Transactions::paginate($limit);
        return response()->json($data);
    }

    public static function getDetail($id)
    {
        $data = Transactions::where('transaction_id', $id)->first();
        return response()->json(new PackagesResource($data));
    }

    public static function saveTransaction()
    {
        try {
            $params = PackageRepository::prepareSaveData(request());
            $connote = Connotes::saveData($params);
            if ($connote) {
                $params['connote_id'] = $connote->connote_id;
                $transaction = Transactions::saveData($params);
                DestinationData::saveData($params);
                OriginData::saveData($params);
            }

            return response()->json(new PackagesResource($transaction));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function updateTransaction($id)
    {
        try {
            $params = PackageRepository::prepareUpdateData(request());
            $transaction = Transactions::updateData($params, $id);
            if ($transaction) {
                Connotes::updateData($params, $transaction->connote_id);
                DestinationData::updateData($params, $transaction->location_id);
                OriginData::updateData($params, $transaction->location_id);

                return response()->json(new PackagesResource($transaction));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function updatePaymentStatus($id)
    {
        try {
            $params = PackageRepository::prepareUpdateData(request());
            $transaction = Transactions::where('transaction_id', $id)->first();
            if ($transaction) {
                $connote = Connotes::where('transaction_id', $id)->first();
                $connote->fill([
                    "connote_state"     => $params['transaction_state'],
                    "connote_state_id"  => $params['transaction_state_id']
                ]);
                $connote->save();

                $transaction->fill(request()->only(['transaction_state']));
                $transaction->save();

                return response()->json(new PackagesResource($transaction));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function saveKoli($id)
    {
        $params = request();
        try {
            $transaction = Transactions::where('transaction_id', $id)->first();
            if ($transaction) {
                $connote = Connotes::where('connote_id', $transaction->connote_id)->first();
                $params['connote_id']   = $connote->connote_id;
                $params['connote_code'] = $connote->connote_code;

                $save = Kolis::saveData($params);
                if ($save) {
                    $koliAll = Kolis::where('connote_id', $connote->connote_id)->get();
                    $surcharge_amount = 0;
                    foreach ($koliAll as $value) {
                        if (count($value->koli_surcharge) > 0) {
                            $surcharge_amount += count($value->koli_surcharge);
                        }
                    }
                    $connote->connote_total_package = count($koliAll);
                    $connote->save();
                    return response()->json(new PackagesResource($transaction));
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function updateKoli($id)
    {
        $params = request();
        try {
            $update = Kolis::updateData($params, $id);
            if ($update) {
                $transaction = Transactions::where('connote_id', $update->connote_id)->first();
                return response()->json(new PackagesResource($transaction));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function destroy($id)
    {
        try {
            $transaction = Transactions::where('transaction_id', $id)->first();
            if ($transaction) {
                $transaction->connote->delete();
                $transaction->destination->delete();
                $transaction->origin->delete();
                if ($transaction->koli) {
                    foreach ($transaction->koli as $koli) {
                        $koli->delete();
                    }
                }
                return $transaction->delete();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private static function prepareSaveData($params)
    {
        // transaction
        $orderTransaction   = Transactions::getCountOrder() + 1;
        $transactionId      = Transactions::generateUUID();
        $locationId         = Str::random(24);
        $transactionCode    = Transactions::generateTransactionCode($params, $orderTransaction);
        $paymentType        = self::getPaymentType($params['transaction_payment_type']);
        $stateType          = self::getStateType($params['transaction_state_id']);
        $location           = self::getAgentLocation($params['location_code']);

        $params['transaction_id']       = $transactionId;
        $params['transaction_code']     = $transactionCode;
        $params['transaction_order']    = $orderTransaction;
        $params['location_id']          = $locationId;
        $params['transaction_state']    = $stateType;
        $params['payment_type']         = $paymentType;
        $params['location_name']        = $location['name'];
        $params['location_type']        = $location['type'];
        $params['location_connote_type']= $location['location_type'];

        // connote
        $orderConnote       = Connotes::getConnoteOrder() + 1;
        $connoteId          = Connotes::generateUUID();
        $connoteCode        = Connotes::generateConnoteCode($params);

        $params['connote_id']           = $connoteId;
        $params['connote_code']         = $connoteCode;
        $params['connote_order']        = $orderConnote;

        return $params;
    }

    private static function prepareUpdateData($params)
    {
        $paymentType        = self::getPaymentType($params['transaction_payment_type']);
        $stateType          = self::getStateType($params['transaction_state_id']);
        $location           = self::getAgentLocation($params['location_code']);

        $params['transaction_state']    = $stateType;
        $params['payment_type']         = $paymentType;
        $params['location_name']        = $location['name'];
        $params['location_type']        = $location['type'];
        $params['location_connote_type']= $location['location_type'];

        return $params;
    }

    private static function getPaymentType($paymentCode)
    {
        $paymentType = array(
            '29' => 'Invoice'
        );

        return $paymentType[$paymentCode];
    }

    private static function getStateType($stateId)
    {
        $stateType = array(
            '1' => 'UNPAID',
            '2' => 'PAID'
        );

        return $stateType[$stateId];
    }

    private static function getAgentLocation($locationCode)
    {
        $location = array(
            'JKTS01' => array(
                'name'          => "Hub Jakarta Selatan",
                'type'          => "Agent",
                'location_type' => "HUB"
            )
        );

        return $location[$locationCode];
    }
}
