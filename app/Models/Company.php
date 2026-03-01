<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'name', 'distribution_type', 'webhook_url'];

    /**
     * Get all of the channelAccounts for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channel_accounts(): HasMany
    {
        return $this->hasMany(ChannelAccount::class);
    }
    /**
     * Get all of the channelAccounts for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channel_pages(): HasMany
    {
        return $this->hasMany(ChannelPage::class);
    }

    public function company_modules(): HasMany
    {
        return $this->hasMany(CompanyModule::class);
    }

    /**
     * Get the api that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function api(): BelongsTo
    {
        return $this->belongsTo(CompanyApi::class, 'id', 'company_id');
    }


    public function hasModule(string $nameOrSlug): bool
    {
        $needle = mb_strtolower($nameOrSlug);

        return DB::table('company_modules as cm')
            ->join('modules as m', 'm.id', '=', 'cm.module_id')
            ->where('cm.company_id', $this->id)
            ->where('cm.status', 1) // aktif
            ->where(function ($q) {
                $q->whereNull('cm.expire_date')
                    ->orWhere('cm.expire_date', '>=', Carbon::now());
            })
            ->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(m.slug) = ?', [$needle])
                    ->orWhereRaw('LOWER(m.name) = ?', [$needle]);
            })
            ->exists();
    }

    public function activeModules()
    {
        return DB::table('company_modules as cm')
            ->join('modules as m', 'm.id', '=', 'cm.module_id')
            ->where('cm.company_id', $this->id)
            ->where('cm.status', 1)
            ->where(function ($q) {
                $q->whereNull('cm.expire_date')
                    ->orWhere('cm.expire_date', '>=', now());
            })
            ->select('m.id', 'm.name', 'm.slug')
            ->orderBy('m.name')
            ->get();
    }

}
