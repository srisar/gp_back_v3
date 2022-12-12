<?php

namespace App\ModelHelpers;

class UserHelper
{
	public const ROLE_ADMIN = "ADMIN";
	public const ROLE_MANAGER = "MANAGER";
	public const ROLE_USER = "USER";

	public const ROLES_ALL = [ self::ROLE_ADMIN, self::ROLE_MANAGER, self::ROLE_USER ];
	public const ROLES_ADMIN_MANAGER = [ self::ROLE_ADMIN, self::ROLE_MANAGER ];
	public const ROLES_ADMIN = [ self::ROLE_ADMIN ];
	public const ROLES_USER = [ self::ROLE_USER ];

	public const STATUS_ACTIVE = 'ACTIVE';
	public const STATUS_INACTIVE = 'INACTIVE';
}