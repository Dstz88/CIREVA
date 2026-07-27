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
 * @property string $module
 * @property string $action
 * @property string $description
 * @property string $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserId($value)
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $booking_code
 * @property numeric $total_amount
 * @property \App\Enums\BookingStatus $status
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookingItem> $bookingItems
 * @property-read int|null $booking_items_count
 * @property-read \App\Models\Transaction|null $transaction
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking confirmed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereBookingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking withoutTrashed()
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $booking_id
 * @property int $ticket_id
 * @property int $quantity
 * @property numeric $price
 * @property numeric $subtotal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking|null $booking
 * @property-read \App\Models\Ticket|null $ticket
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingItem whereUpdatedAt($value)
 */
	class BookingItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $organizer_profile_id
 * @property string $agreement_number
 * @property string $version
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $signed_at
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $rejected_reason
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property \App\Enums\SpkStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\OrganizerProfile|null $organizerProfile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereAgreementNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereOrganizerProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereRejectedReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereSignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CooperationAgreement whereVersion($value)
 */
	class CooperationAgreement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $organizer_profile_id
 * @property int $category_id
 * @property int $location_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $banner
 * @property \App\Enums\eventStatus $status
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property int|null $published_by
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\eventCategory $category
 * @property-read \App\Models\eventLocation $location
 * @property-read \App\Models\OrganizerProfile|null $organizerProfile
 * @property-read \App\Models\User|null $publisher
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\eventSchedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event draft()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event ongoing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereOrganizerProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event wherePublishedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|event withoutTrashed()
 */
	class event extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\event> $events
 * @property-read int|null $events_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventCategory whereUpdatedAt($value)
 */
	class eventCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property numeric $latitude
 * @property numeric $longitude
 * @property int|null $capacity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\event> $events
 * @property-read int|null $events_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventLocation whereUpdatedAt($value)
 */
	class eventLocation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon $start_datetime
 * @property \Illuminate\Support\Carbon $end_datetime
 * @property string $timezone
 * @property \App\Enums\ScheduleStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\event|null $event
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule whereEndDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule whereeventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule whereStartDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|eventSchedule whereUpdatedAt($value)
 */
	class eventSchedule extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $message
 * @property bool $is_read
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $organizer_profile_id
 * @property string $document_type
 * @property string $file_path
 * @property \App\Enums\DocumentStatus $verification_status
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OrganizerProfile|null $organizerProfile
 * @property-read \App\Models\User|null $verifier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereOrganizerProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereVerificationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerDocument whereVerifiedBy($value)
 */
	class OrganizerDocument extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $organization_name
 * @property string $owner_name
 * @property string $phone
 * @property string $address
 * @property string $description
 * @property string $logo
 * @property \App\Enums\OrganizerStatus $status
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CooperationAgreement> $agreements
 * @property-read int|null $agreements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrganizerDocument> $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\event> $events
 * @property-read int|null $events_count
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\User|null $verifier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereOrganizationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereOwnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile whereVerifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizerProfile withoutTrashed()
 */
	class OrganizerProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $transaction_id
 * @property string $file_path
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \App\Enums\PaymentProofStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Transaction $transaction
 * @property-read \App\Models\User|null $verifier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereVerifiedBy($value)
 */
	class PaymentProof extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $report_type
 * @property int $generated_by
 * @property \Illuminate\Support\Carbon $generated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereGeneratedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereGeneratedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUpdatedAt($value)
 */
	class Report extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RoleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string $name
 * @property string|null $description
 * @property numeric $price
 * @property int $quota
 * @property int $sold
 * @property \App\Enums\TicketStatus $status
 * @property string|null $sale_start
 * @property string|null $sale_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookingItem> $bookingItems
 * @property-read int|null $booking_items_count
 * @property-read \App\Models\event|null $event
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereeventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereSaleEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereSaleStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereSold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket withoutTrashed()
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $booking_id
 * @property string $transaction_number
 * @property string $payment_method
 * @property numeric $amount
 * @property \App\Enums\TransactionStatus $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking|null $booking
 * @property-read \App\Models\PaymentProof|null $paymentProof
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction paid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereTransactionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivityLog> $activityLogs
 * @property-read int|null $activity_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\OrganizerProfile|null $organizerProfile
 * @property-read \App\Models\Role $role
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

