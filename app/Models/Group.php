<?php

namespace App\Models;

use App\Enum\DayWeekEnum;
use App\Enum\StatePaymentEnum;
use App\Traits\Observable;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Group extends Model
{
    use HasFactory, Sluggable, Observable;

    protected $table        = 'groups';
    protected $primaryKey   = 'id_group';

    protected $guarded  = [
        'id_group',
        'day_payment',
        'slug',
        'state_archived_group'
    ];

    protected $fillable = [
        'name_group',
        'created_group',
        'day_payment',
        'id_beneficiary'
    ];

    protected $casts    = [
        'created_group'         => 'date',
        'state_archived_group'  => 'boolean',
        'day_payment'           => DayWeekEnum::class
    ];

    protected $appends  = ['day_payment_name'];

    /**
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name_group'
            ]
        ];
    }

    public function nameGroup(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => Str::lower($value),
        );
    }

    public function dayPaymentName(): Attribute
    {
        return new Attribute(
            get: fn () => DayWeekEnum::getLabel($this->day_payment),
        );
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'id_beneficiary', 'id_beneficiary');
    }

    public function borrowers()
    {
        return $this->belongsToMany(Borrower::class, GroupBorrower::class, 'id_group', 'id_borrower')
            ->as('group_borrower')
            ->withPivot(['id_group_borrower', 'amount_borrow', 'amount_interest', 'state_borrow', 'number_payments'])
            ->withTimestamps();
    }

    public function groupBorrowers()
    {
        return $this->hasMany(GroupBorrower::class, 'id_group', 'id_group');
    }

    public function paymentsPaid()
    {
        return $this->hasManyThrough(Payment::class, GroupBorrower::class, 'id_group', 'id_group_borrower')
            ->where('state_payment', '=', StatePaymentEnum::STATUS_PAID);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, GroupBorrower::class, 'id_group', 'id_group_borrower');
    }

    public function paymentsPastDueGroup($search)
    {
        return $this->payments()
            ->with(['borrower' => function ($query) {
                $query->select('borrowers.id_borrower', 'borrowers.name_borrower', 'borrowers.last_name_borrower', 'borrowers.slug');
            }])
            ->where('state_payment', '!=', StatePaymentEnum::STATUS_PAID)
            ->where('date_payment', '<', Carbon::now())
            ->whereHas('borrower', function ($query) use ($search) {
                $query->where(DB::raw("concat(borrowers.name_borrower, ' ', borrowers.last_name_borrower)"), 'LIKE', $search . "%");
            })
            ->orderBy('id_group_borrower', 'ASC');
    }

    public function paymentsNextDueGroup($search)
    {
        $current_date = Carbon::now();
        $next_week_date = Carbon::now()->addDays(7);

        return $this->payments()
            ->with(['borrower' => function ($query) {
                $query->select('borrowers.id_borrower', 'borrowers.name_borrower', 'borrowers.last_name_borrower', 'borrowers.slug');
            }])
            ->where('state_payment', '=', StatePaymentEnum::STATUS_INPROCCESS)
            ->whereBetween('date_payment', [$current_date, $next_week_date])
            ->whereHas('borrower', function ($query) use ($search) {
                $query->where(DB::raw("concat(borrowers.name_borrower, ' ', borrowers.last_name_borrower)"), 'LIKE', $search . "%");
            })
            ->orderBy('id_group_borrower', 'ASC');
    }
}
