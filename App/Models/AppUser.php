<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $password_hash
 * @property string $email
 * @property string $full_name
 * @property string $profile_pic
 * @property string $role
 * @property string $status
 *
 * @mixin Builder
 */
class AppUser extends Model
{
	protected $table = "users";

	protected $fillable = [
		"full_name",
		"email",
		"password_hash",
		"role",
	];

	protected $hidden = [ "password_hash" ];
}
