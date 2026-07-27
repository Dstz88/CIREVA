<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use App\Models\OrganizerProfile;
use App\Models\CooperationAgreement;
use App\Models\eventCategory;
use App\Models\eventLocation;
use App\Models\event;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Transaction;
use App\Enums\OrganizerStatus;
use App\Enums\SpkStatus;
use App\Enums\eventStatus;
use App\Enums\TicketStatus;
use App\Enums\BookingStatus;
use App\Enums\TransactionStatus;

class NotificationReportModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_and_read_notifications(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $n1 = Notification::create([
            'user_id' => $user->id,
            'title' => 'Pembayaran Berhasil',
            'message' => 'Tiket telah diterbitkan.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->get(route('notifications.index'));
        $response->assertStatus(200);
        $response->assertSee('Pembayaran Berhasil');

        // Mark single as read
        $readResponse = $this->actingAs($user)->put(route('notifications.read', $n1->id));
        $readResponse->assertRedirect();

        $n1->refresh();
        $this->assertTrue((bool)$n1->is_read);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Notif 1',
            'message' => 'Message 1',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Notif 2',
            'message' => 'Message 2',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->post(route('notifications.read-all'));
        $response->assertRedirect();

        $unreadCount = Notification::where('user_id', $user->id)->where('is_read', false)->count();
        $this->assertEquals(0, $unreadCount);
    }

    public function test_organizer_sales_report_calculation(): void
    {
        $organizerUser = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $organizerUser->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Bambang',
            'phone' => '08123456789',
            'status' => OrganizerStatus::Approved,
        ]);

        CooperationAgreement::create([
            'organizer_profile_id' => $profile->id,
            'agreement_number' => 'SPK-RPT-001',
            'version' => 'v1.0',
            'status' => SpkStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Wayang', 'slug' => 'wayang-rpt']);
        $location = eventLocation::create(['name' => 'Alun-alun', 'address' => 'Jl. Alun-alun', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Pentas Wayang',
            'slug' => 'pentas-wayang-rpt',
            'description' => 'Deskripsi.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Published,
        ]);

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'name' => 'VIP Wayang',
            'price' => 100000,
            'quota' => 50,
            'sold' => 2,
            'status' => TicketStatus::Active,
        ]);

        $buyer = User::factory()->create(['role' => 'user']);
        $booking = Booking::create([
            'user_id' => $buyer->id,
            'booking_code' => 'BKG-RPT-001',
            'total_amount' => 200000,
            'status' => BookingStatus::PaymentCompleted,
        ]);

        BookingItem::create([
            'booking_id' => $booking->id,
            'ticket_id' => $ticket->id,
            'quantity' => 2,
            'price' => 100000,
            'subtotal' => 200000,
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'transaction_number' => 'TRX-RPT-001',
            'payment_method' => 'BNI',
            'amount' => 200000,
            'status' => TransactionStatus::Success,
        ]);

        $response = $this->actingAs($organizerUser)->get(route('organizer.reports.index'));
        $response->assertStatus(200);
        $response->assertViewHas('grossRevenue', 200000);
        $response->assertViewHas('platformFee', 30000); // 15% of 200k = 30k
        $response->assertViewHas('netRevenue', 170000); // 200k - 30k = 170k
    }

    public function test_admin_global_report_calculation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $organizerUser = User::factory()->create(['role' => 'organizer']);

        $profile = OrganizerProfile::create([
            'user_id' => $organizerUser->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Bambang',
            'phone' => '08123456789',
            'status' => OrganizerStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Tari Topeng', 'slug' => 'tari-topeng-rpt']);
        $location = eventLocation::create(['name' => 'Gedung Kesenian', 'address' => 'Jl. Pemuda', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Tari Topeng Cirebon',
            'slug' => 'tari-topeng-rpt',
            'description' => 'Deskripsi.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Published,
        ]);

        $buyer = User::factory()->create(['role' => 'user']);
        $booking = Booking::create([
            'user_id' => $buyer->id,
            'booking_code' => 'BKG-RPT-002',
            'total_amount' => 1000000,
            'status' => BookingStatus::PaymentCompleted,
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'transaction_number' => 'TRX-RPT-002',
            'payment_method' => 'BCA',
            'amount' => 1000000,
            'status' => TransactionStatus::Success,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.index'));
        $response->assertStatus(200);
        $response->assertViewHas('grossRevenue', 1000000);
        $response->assertViewHas('platformCommission', 150000); // 15% of 1M = 150k
        $response->assertViewHas('organizerPayout', 850000);
    }
}
