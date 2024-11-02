<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use SolutionForest\FilamentAccessManagement\Concerns\FilamentUserHelpers;
use App\Models\ModelHasRole;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use function Psy\debug;

class User extends Authenticatable
{
    use LogsActivity;
    use HasApiTokens, HasFactory, Notifiable;
    use FilamentUserHelpers;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {

//        static::retrieved(function (User $user) {
//            $user->api_token = null;//
//        });

        static::created(function (User $user) {
            // 在 UserLogs 表中記錄用戶創建事件
            ModelHasRole::create([
                'model_id' => $user->id,
                'role_id' => '2',
                'is_admin' => '0',
                'model_type'=>'App\Models\User'
            ]);
        });

//        Gate::before(function (User $user, string $ability) {
//            return $user->isSuperAdmin() ? true: null;
//        });
    }

    public function getActivitylogOptions(): LogOptions
    {

        return LogOptions::defaults()
            ->logOnly(['key', 'value']);
        // Chain fluent methods for configuration options
    }
}
