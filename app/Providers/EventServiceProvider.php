<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\Beneficiary;
use App\Observers\BeneficiaryObserver;
use App\Observers\IndvidualPaymentObserver;

use App\Models\Borrower;
use App\Models\IndividualPayment;
use App\Models\Payment;
use App\Observers\BorrowerObserver;
use App\Observers\PaymentObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        /* individual observers */
        Beneficiary::observe(BeneficiaryObserver::class);
        Borrower::observe(BorrowerObserver::class);
        Payment::observe(PaymentObserver::class);
        IndividualPayment::observe(IndvidualPaymentObserver::class);
        /* multiple observer */
        //
    }
}
