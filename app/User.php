<?php

namespace CVS;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    protected $table = 'users';
    protected $guarded = ['id'];
    protected $hidden = ['id', 'profile_id', 'password', 'remember_token'];
    protected $appends = ['ido', 'phone_formatted'];
    protected $casts = ['organizer' => 'boolean', 'sms_notifications' => 'boolean', 'email_notifications' => 'boolean'];

    public function getRouteKey()
    {
        return app('Optimus')->encode($this->getKey());
    }

    public function profile()
    {
        return $this->morphTo();
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function belongsToCompany(Company $company)
    {
        return (isset($this->profile->company_id) && $this->profile->company_id == $company->id);
    }

    public function getIdoAttribute()
    {
        return app('Optimus')->encode($this->id);
    }

    public function getPhoneFormattedAttribute()
    {
        return self::getNationalPhoneNumber($this->phone);
    }

    public static function getInternationalPhoneNumber($phoneNumber)
    {
        $phoneUtils = app('PhoneUtils');

        try {
            $phoneNumberProto = $phoneUtils->parse($phoneNumber, "FR");
            return $phoneUtils->format($phoneNumberProto, \libphonenumber\PhoneNumberFormat::E164);
        } catch (\libphonenumber\NumberParseException $e) {
            return NULL;
        }
    }

    public static function getNationalPhoneNumber($phoneNumber)
    {
        $phoneUtils = app('PhoneUtils');

        try {
            $phoneNumberProto = $phoneUtils->parse($phoneNumber, "FR");
            return $phoneUtils->format($phoneNumberProto, \libphonenumber\PhoneNumberFormat::NATIONAL);
        } catch (\libphonenumber\NumberParseException $e) {
            return NULL;
        }
    }
}
