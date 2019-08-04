<?php


namespace App\Models;

use App\Events\InvoicePayment;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $connection = 'mysql';

    public function getUnpaidInvoices(bool $isCount = false, int $skip = 0, int $limit = PHP_INT_MAX): array
    {
        if ($isCount) {
            return [Invoice::where('isPaid', 0)->count()];
        }

        $invoices = Invoice::where('isPaid', 0)
            ->skip($skip)
            ->take($limit)
            ->get();

        if (!empty($invoices)) {
            $invoices = $invoices->toArray();
        }
        return $invoices ?? [];
    }

    /**
     * Used to trigger an Event when the invoice payment is done and isPaid is set to True successfully.
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function($invoice) {
            if ($invoice->isPaid == true) {
                event(new InvoicePayment($invoice));
            }
        });
    }
}
