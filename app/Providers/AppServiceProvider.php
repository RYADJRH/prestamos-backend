<?php

namespace App\Providers;

use App\Models\Beneficiary;
use App\Observers\BeneficiaryObserver;

use App\Models\Borrower;
use App\Models\Payment;
use App\Observers\BorrowerObserver;
use App\Observers\PaymentObserver;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Beneficiary::observe(BeneficiaryObserver::class);
        Borrower::observe(BorrowerObserver::class);
        Payment::observe(PaymentObserver::class);

        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
    }
}
