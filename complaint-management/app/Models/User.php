<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'title',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the complaints for the user.
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Update user title based on complaint count.
     */
    public function updateTitle()
    {
        $complaintCount = $this->complaints()->count();
        
        if ($complaintCount >= 10) {
            $this->title = 'Veteran Complainer';
        } elseif ($complaintCount >= 4) {
            $this->title = 'Active Contributor';
        } else {
            $this->title = 'Newcomer';
        }
        
        $this->save();
    }

    /**
     * Get the title color based on the user's title.
     */
    public function getTitleColorAttribute()
    {
        return match($this->title) {
            'Veteran Complainer' => 'text-purple-600',
            'Active Contributor' => 'text-blue-600',
            'Newcomer' => 'text-green-600',
            default => 'text-gray-600',
        };
    }
}
