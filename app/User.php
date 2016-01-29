<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, ObfuscatedIdTrait;

    protected $table = 'users';
    protected $guarded = ['id'];
    protected $hidden = ['id', 'profile_id', 'password', 'remember_token'];
    protected $appends = ['ido', 'phone_formatted', 'profile_type_str'];
    protected $casts = ['organizer' => 'boolean', 'sms_notifications' => 'boolean', 'email_notifications' => 'boolean'];

    public function getRouteKey()
    {
        return app('Hashids')->encode(self::class . '-' . $this->id);
    }

    public function profile()
    {
        return $this->morphTo();
    }

    public function sentTexts()
    {
        return $this->hasMany(HistoryText::class, 'user_id');
    }

    public function sentEmails()
    {
        return $this->hasMany(HistoryEmail::class, 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * @param Company $company
     * Check if the User belongs to the specified Company
     * @return bool
     */
    public function belongsToCompany(Company $company)
    {
        return (isset($this->profile->company_id) && $this->profile->company_id == $company->id);
    }

    public function getPhoneFormattedAttribute()
    {
        return self::getNationalPhoneNumber($this->phone);
    }

    public function getProfileTypeStrAttribute()
    {
        return strtolower(str_replace('CVS\\', '', $this->profile_type));
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

    public static function getAllIdos()
    {
        $users = self::all();

        $usersIdos = [];
        foreach($users as $user)
            $usersIdos[] = $user->ido;

        return $usersIdos;
    }

}
