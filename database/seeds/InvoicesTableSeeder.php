<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Core\Constants;

class InvoicesTableSeeder extends Seeder
{
    /**
     * Sample invoices with due dates in future, today and yesterday. 1 is already paid and rest unpaid
     */

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('invoices')->insert([
            'account_nr' => 'A101',
            'title' => 'Due Future Bill',
            'tenant_phone_number' => '+97133034505',
            'tenant_email' => 'toby@gmail.com',
            'owner_phone_number' => '97133030000',
            'owner_email' => 'betterhomes@gmail.com',
            'path' => 'https://s3-eu-west-1.amazonaws.com/invoices/sept/A101.pdf',
            'amount' => 5000,
            'currency' => 'AED',
            'due_date' => date('Y-m-d', strtotime("+20 days")),
            'isPaid' => false,
            'created_at' => date(Constants::DATE_FORMAT),
            'updated_at' => date(Constants::DATE_FORMAT),
        ]);

        DB::table('invoices')->insert([
            'account_nr' => 'A102',
            'title' => 'Due today Bill',
            'tenant_phone_number' => '+97155034505',
            'tenant_email' => 'jaai@gmail.com',
            'owner_phone_number' => '97133030000',
            'owner_email' => 'betterhomes@gmail.com',
            'path' => 'https://s3-eu-west-1.amazonaws.com/invoices/aug/A102.pdf',
            'amount' => 3000,
            'currency' => 'SAR',
            'due_date' => date('Y-m-d'),
            'isPaid' => false,
            'created_at' => date(Constants::DATE_FORMAT, strtotime("-20 days")),
            'updated_at' => date(Constants::DATE_FORMAT, strtotime("-20 days")),
        ]);

        DB::table('invoices')->insert([
            'account_nr' => 'A103',
            'title' => 'Past Due Bill',
            'tenant_phone_number' => '+97155039860',
            'tenant_email' => 'ryan@gmail.com',
            'owner_phone_number' => '97133030000',
            'owner_email' => 'betterhomes@gmail.com',
            'path' => 'https://s3-eu-west-1.amazonaws.com/invoices/aug/A103.pdf',
            'amount' => 300.5,
            'currency' => 'KWD',
            'due_date' => date('Y-m-d', strtotime("-1 days")),
            'isPaid' => false,
            'created_at' => date(Constants::DATE_FORMAT, strtotime("-22 days")),
            'updated_at' => date(Constants::DATE_FORMAT, strtotime("-22 days")),
        ]);

        DB::table('invoices')->insert([
            'account_nr' => 'A104',
            'title' => 'Future Paid Bill',
            'tenant_phone_number' => '+97155078860',
            'tenant_email' => 'tom@gmail.com',
            'owner_phone_number' => '97133030000',
            'owner_email' => 'betterhomes@gmail.com',
            'path' => 'https://s3-eu-west-1.amazonaws.com/invoices/aug/A104.pdf',
            'amount' => 3000,
            'currency' => 'SAR',
            'due_date' => date('Y-m-d', strtotime("+10 days")),
            'isPaid' => true,
            'created_at' => date(Constants::DATE_FORMAT, strtotime("-20 days")),
            'updated_at' => date(Constants::DATE_FORMAT, strtotime("-20 days")),
        ]);
    }
}
