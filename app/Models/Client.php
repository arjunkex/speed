<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'client_id', 'email', 'phone', 'company_name', 'address', 'status', 'image_path',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    // return client invoices total
    public function cilentInvoiceTotal()
    {
        $invoiceTotal = 0;
        $invoices = $this->clientInvoices;
        if ($invoices) {
            $invoiceTotal = $invoices->where('status', 1)->sum('calculated_total');
            //$invoiceTotal = $invoices->sum('sub_total') + $invoices->sum('transport') + $invoices->sum('calculated_tax') - $invoices->sum('discount');
        }

        return $invoiceTotal;
    }

    // return client total paid
    public function clientTotalPaid()
    {
        $totalPaid = 0;
        if (isset($this->invoicePayments)) {
            $totalPaid = $this->invoicePayments->where('status', 1)->sum('amount');
        }

        return $totalPaid;
    }

    // return client due
    public function clientDue()
    {
        $due = $this->clientInvoices->sum('calculated_due');

        return $due;
    }

    // return client sub total
    public function cilentSubTotal()
    {
        $subTotal = 0;
        $invoices = $this->clientInvoices;
        if ($invoices) {
            $subTotal = $invoices->sum('sub_total');
        }

        return $subTotal;
    }

    // return client total discout
    public function cilentInvoiceDiscount()
    {
        $discount = 0;
        $invoices = $this->clientInvoices;
        if ($invoices) {
            $discount = $invoices->sum('discount');
        }

        return $discount;
    }

    // return client total transport cost
    public function cilentInvoiceTransportCost()
    {
        $transportCost = 0;
        $invoices = $this->clientInvoices;
        if ($invoices) {
            $transportCost = $invoices->sum('transport');
        }

        return $transportCost;
    }

    // return client total non invoice due
    public function nonInvoiceTotalDue()
    {
        $totalDue = 0;
        $dues = $this->clientNonInvoiceDues;
        if (isset($dues)) {
            $totalDue = $dues->where('status', 1)->sum('amount');
        }

        return $totalDue;
    }

    // return client total non invoice paid
    public function nonInvoicePaid()
    {
        $totaPaid = 0;
        $paid = $this->clientNonInvoicePayments;
        if (isset($paid)) {
            $totaPaid = $paid->where('status', 1)->sum('amount');
        }

        return $totaPaid;
    }

    // return client total non invoice current due
    public function nonInvoiceCurrentDue()
    {
        return $this->nonInvoiceTotalDue() - $this->nonInvoicePaid();
    }

    /**
     * Get the non invoice dues
     */
    public function clientNonInvoiceDues()
    {
        return $this->hasMany(NonInvoicePayment::class, 'client_id')->where('type', 0);
    }

    /**
     * Get the non invoice payments
     */
    public function clientNonInvoicePayments()
    {
        return $this->hasMany(NonInvoicePayment::class, 'client_id')->where('type', 1);
    }

    /**
     * Get the invoices.
     */
    public function clientInvoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    /**
     * Get the invoice payements for the client.
     */
    public function invoicePayments()
    {
        return $this->hasManyThrough(InvoicePayment::class, Invoice::class, 'client_id', 'invoice_id');
    }
}
