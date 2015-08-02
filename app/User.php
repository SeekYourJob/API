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
    protected $hidden = ['password', 'remember_token'];

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
