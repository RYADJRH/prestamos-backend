<?php

namespace App\Providers;

use App\Models\Beneficiary;
use App\Observers\BeneficiaryObserver;

use App\Models\Borrower;
use App\Observers\BorrowerObserver;
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
    }
}
