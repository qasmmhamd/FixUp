<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property numeric $latitude
 * @property numeric $longitude
 * @property string $detailed_address
 * @property int|null $area_address_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaAddress|null $areaAddress
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAreaAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereDetailedAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUserId($value)
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * @class AreaAddress
 *
 * Represents a geographical area or location in the FixUp system.
 * Areas are used to organize services and workers by location,
 * helping customers find workers in their specific regions.
 * @property int $id
 * @property string $area_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaAddress whereAreaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaAddress whereUpdatedAt($value)
 */
	class AreaAddress extends \Eloquent {}
}

namespace App\Models{
/**
 * @class Career
 *
 * Represents a professional career or job category in the FixUp system.
 * Careers group related services and workers who specialize in
 * particular professional fields.
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Service> $services
 * @property-read int|null $services_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Worker> $workers
 * @property-read int|null $workers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Career newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Career newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Career query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Career whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Career whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Career whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Career whereUpdatedAt($value)
 */
	class Career extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $receiver
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereUpdatedAt($value)
 */
	class Chat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $worker_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\Worker $worker
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryWork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryWork newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryWork query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryWork whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryWork whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryWork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryWork whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryWork whereWorkerId($value)
 */
	class GalleryWork extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $order_id
 * @property int|null $worker_id
 * @property int|null $gallery_work_id
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GalleryWork|null $galleryWork
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Worker|null $worker
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereGalleryWorkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereWorkerId($value)
 */
	class Image extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $worker_id
 * @property int $order_id
 * @property numeric $invoice_value
 * @property numeric|null $payment_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Worker $worker
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereInvoiceValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice wherePaymentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereWorkerId($value)
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $description
 * @property int $priority
 * @property string $status
 * @property \Illuminate\Support\Carbon $expires_at
 * @property int|null $address_id
 * @property int $career_id
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\Career $career
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\Invoice|null $invoice
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PriceOffer> $offers
 * @property-read int|null $offers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PriceOffer> $priceOffers
 * @property-read int|null $price_offers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rating> $ratings
 * @property-read int|null $ratings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Service> $services
 * @property-read int|null $services_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Worker|null $worker
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCareerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $worker_id
 * @property int $order_id
 * @property numeric $price
 * @property string $time_range
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Worker $worker
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer whereTimeRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceOffer whereWorkerId($value)
 */
	class PriceOffer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $worker_id
 * @property int $order_id
 * @property int $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Worker $worker
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereWorkerId($value)
 */
	class Rating extends \Eloquent {}
}

namespace App\Models{
/**
 * @class Service
 *
 * Represents a specific service that can be offered by workers.
 * Services are categorized under careers and can be selected
 * by workers to indicate their expertise areas.
 * @property int $id
 * @property int $career_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Career $career
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Worker> $workers
 * @property-read int|null $workers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCareerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $message
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticMessage whereUpdatedAt($value)
 */
	class StaticMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_active
 * @property string|null $phone_number
 * @property string|null $profile_image
 * @property string|null $birth_date
 * @property string $role
 * @property-read \App\Models\Address|null $address
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Worker|null $worker
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @class Worker
 *
 * Represents a worker profile in the FixUp system.
 * Workers are users who provide professional services to customers.
 * This model manages worker profiles, career information, and service offerings.
 * @property int $id
 * @property int $user_id
 * @property int $career_id
 * @property string|null $about
 * @property string $status
 * @property int|null $years_experience
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Career $career
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PriceOffer> $offers
 * @property-read int|null $offers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Service> $services
 * @property-read int|null $services_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker status($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker whereCareerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Worker whereYearsExperience($value)
 */
	class Worker extends \Eloquent {}
}

