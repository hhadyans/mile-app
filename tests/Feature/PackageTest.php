<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Transactions;
use App\Models\Kolis;

class PackageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetTransaction()
    {
        $response = $this->get(route('api.get.transaction'));

        $response->assertStatus(200);
    }

    public function testStoreTransaction()
    {
        $response = $this->post(route('api.post.transaction'), [
            "customer_name" => "PT. AMARA PRIMATIGA",
            "customer_code" => 1678593,
            "transaction_amount" => 70700,
            "transaction_discount" => 0,
            "transaction_additional_field" => "",
            "transaction_payment_type" => 29,
            "transaction_state_id" => 2,
            "organization_id" => 6,
            "transaction_cash_amount" => 0,
            "transaction_cash_change" => 0,
            "actual_weight" => 20,
            "volume_weight" => 0,
            "chargable_weight" => 20,
            "sla_day" => 2,
            "source_tariff_db" => "tariff_customers",
            "id_source_tariff" => 1576868,
            "location_code" => "JKTS01",
            "pod" => null,
            "history" => [],
            "custom_field" => array([
                "catatan_tambahan" => "JANGAN DI BANTING / DI TINDIH"
            ]),
            "connote_number" => 1,
            "customer_attribute" => array([
                "nama_sales" => "Radit Fitrawikarsa",
                "top" => "14 Hari",
                "jenis_pelanggan" => "B2B"
            ]),
            "origin_data" => array([
                "customer_name" => "PT. NARA OKA PRAKARSA",
                "customer_address" => "JL. KH. AHMAD DAHLAN NO. 100, SEMARANG TENGAH 12420",
                "customer_email" => "info@naraoka.co.id",
                "customer_phone" => "024-1234567",
                "customer_address_detail" => null,
                "customer_zip_code" => "12420",
                "zone_code" => "CGKFT",
            ]),
            "destination_data" => array([
                "customer_name" => "PT AMARIS HOTEL SIMPANG LIMA",
                "customer_address" => "JL. KH. AHMAD DAHLAN NO. 01, SEMARANG TENGAH",
                "customer_email" => null,
                "customer_phone" => "0248453499",
                "customer_address_detail" => "KOTA SEMARANG SEMARANG TENGAH KARANGKIDUL",
                "customer_zip_code" => "50241",
                "zone_code" => "SMG",
            ])
        ]);

        $response->assertStatus(200);
    }

    public function testStoreKoli()
    {
        $transaction = Transactions::first();
        $response = $this->post(route('api.post.koli', ["id" => $transaction->transaction_id]), [
            "koli_length" => 0,
            "awb_url" => "https://tracking.mile.app/label/AWB00100209082020.1",
            "koli_chargable_weight" => 9,
            "koli_width" => 0,
            "koli_surcharge" => [],
            "koli_height" => 0,
            "koli_description" => "V WARP",
            "koli_formula_id" => null,
            "koli_volume" => 0,
            "koli_weight" => 9,
            "koli_custom_field" => array([
                "awb_sicepat" => null,
                "harga_barang" => null
            ])
        ]);

        $response->assertStatus(200);
    }

    public function testUpdateTransaction()
    {
        $transaction = Transactions::first();
        $response = $this->put(route('api.put.transaction', ["id" => $transaction->transaction_id]), [
            "customer_name" => "PT. AMARA PRIMATIGA - Edited",
            "customer_code" => 1678593,
            "transaction_amount" => 70700,
            "transaction_discount" => 0,
            "transaction_additional_field" => "",
            "transaction_payment_type" => 29,
            "transaction_state_id" => 2,
            "organization_id" => 6,
            "transaction_cash_amount" => 0,
            "transaction_cash_change" => 0,
            "actual_weight" => 20,
            "volume_weight" => 0,
            "chargable_weight" => 20,
            "sla_day" => 2,
            "source_tariff_db" => "tariff_customers",
            "id_source_tariff" => 1576868,
            "location_code" => "JKTS01",
            "pod" => null,
            "history" => [],
            "custom_field" => array([
                "catatan_tambahan" => "JANGAN DI BANTING / DI TINDIH"
            ]),
            "connote_number" => 1,
            "customer_attribute" => array([
                "nama_sales" => "Radit Fitrawikarsa",
                "top" => "14 Hari",
                "jenis_pelanggan" => "B2B"
            ]),
            "origin_data" => array([
                "customer_name" => "PT. NARA OKA PRAKARSA",
                "customer_address" => "JL. KH. AHMAD DAHLAN NO. 100, SEMARANG TENGAH 12420",
                "customer_email" => "info@naraoka.co.id",
                "customer_phone" => "024-1234567",
                "customer_address_detail" => null,
                "customer_zip_code" => "12420",
                "zone_code" => "CGKFT",
            ]),
            "destination_data" => array([
                "customer_name" => "PT AMARIS HOTEL SIMPANG LIMA",
                "customer_address" => "JL. KH. AHMAD DAHLAN NO. 01, SEMARANG TENGAH",
                "customer_email" => null,
                "customer_phone" => "0248453499",
                "customer_address_detail" => "KOTA SEMARANG SEMARANG TENGAH KARANGKIDUL",
                "customer_zip_code" => "50241",
                "zone_code" => "SMG",
            ])
        ]);

        $response->assertStatus(200);
    }

    public function testUpdateKoli()
    {
        $koli = Kolis::first();
        $response = $this->post(route('api.put.koli', ["id" => $koli->koli_id]), [
            "koli_length" => 0,
            "awb_url" => "https://tracking.mile.app/label/AWB00100209082020.1",
            "koli_chargable_weight" => 9,
            "koli_width" => 0,
            "koli_surcharge" => [],
            "koli_height" => 0,
            "koli_description" => "V WARP - Edited",
            "koli_formula_id" => null,
            "koli_volume" => 0,
            "koli_weight" => 9,
            "koli_custom_field" => array([
                "awb_sicepat" => null,
                "harga_barang" => null
            ])
        ]);

        $response->assertStatus(200);
    }

    public function testUpdatePayment()
    {
        $transaction = Transactions::first();
        $response = $this->patch(route('api.patch.payment', ["id" => $transaction->transaction_id]), [
            "customer_name" => "PT. AMARA PRIMATIGA - Edited",
            "customer_code" => 1678593,
            "transaction_amount" => 70700,
            "transaction_discount" => 0,
            "transaction_additional_field" => "",
            "transaction_payment_type" => 29,
            "transaction_state_id" => 1,
            "organization_id" => 6,
            "transaction_cash_amount" => 0,
            "transaction_cash_change" => 0,
            "actual_weight" => 20,
            "volume_weight" => 0,
            "chargable_weight" => 20,
            "sla_day" => 2,
            "source_tariff_db" => "tariff_customers",
            "id_source_tariff" => 1576868,
            "location_code" => "JKTS01",
            "pod" => null,
            "history" => [],
            "custom_field" => array([
                "catatan_tambahan" => "JANGAN DI BANTING / DI TINDIH"
            ]),
            "connote_number" => 1,
            "customer_attribute" => array([
                "nama_sales" => "Radit Fitrawikarsa",
                "top" => "14 Hari",
                "jenis_pelanggan" => "B2B"
            ]),
            "origin_data" => array([
                "customer_name" => "PT. NARA OKA PRAKARSA",
                "customer_address" => "JL. KH. AHMAD DAHLAN NO. 100, SEMARANG TENGAH 12420",
                "customer_email" => "info@naraoka.co.id",
                "customer_phone" => "024-1234567",
                "customer_address_detail" => null,
                "customer_zip_code" => "12420",
                "zone_code" => "CGKFT",
            ]),
            "destination_data" => array([
                "customer_name" => "PT AMARIS HOTEL SIMPANG LIMA",
                "customer_address" => "JL. KH. AHMAD DAHLAN NO. 01, SEMARANG TENGAH",
                "customer_email" => null,
                "customer_phone" => "0248453499",
                "customer_address_detail" => "KOTA SEMARANG SEMARANG TENGAH KARANGKIDUL",
                "customer_zip_code" => "50241",
                "zone_code" => "SMG",
            ])
        ]);

        $response->assertStatus(200);
    }

    public function testGetDetailTransaction()
    {
        $transaction = Transactions::first();
        $response = $this->get(route('api.detail.transaction', ["id" => $transaction->transaction_id]));

        $response->assertStatus(200);
    }

    public function testDeleteTransaction()
    {
        $transaction = Transactions::first();
        $response = $this->delete(route('api.delete.transaction', ["id" => $transaction->transaction_id]));

        $response->assertStatus(200);
    }
}
