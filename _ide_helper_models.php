<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\LegoSet
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $setNumber
 * @property float $price
 * @property string $imageUrl
 * @property string $externalLink
 * @property int $parts
 * @property float $boughtPrice
 * @property string $boughtAt
 * @property int $year
 * @property string $theme
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet query()
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereBoughtAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereBoughtPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereExternalLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereParts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereSetNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegoSet whereYear($value)
 */
	class LegoSet extends \Eloquent implements \App\Models\LegoSetInterface {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

