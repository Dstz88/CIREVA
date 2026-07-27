<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\OrganizerProfile;
use App\Models\eventCategory;
use App\Models\eventLocation;
use App\Models\event;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Transaction;
use App\Enums\OrganizerStatus;
use App\Enums\eventStatus;
use App\Enums\TicketStatus;
use App\Enums\BookingStatus;
use App\Enums\TransactionStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TransactionModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_payment_proof(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'user']);

        $booking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'BKG-TRX-001',
            'total_amount' => 100000,
            'status' => BookingStatus::Pending,
        ]);

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'transaction_number' => 'TRX-00100',
            'payment_method' => 'Bank Transfer BNI',
            'amount' => 100000,
            'status' => TransactionStatus::Pending,
        ]);

        $file = UploadedFile::fake()->create('bukti_transfer.jpg', 300, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('user.payments.upload', $transaction->id), [
            'proof_file' => $file,
            'notes' => 'Pembayaran via BNI cabang Cirebon',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payment_proofs', [
            'transaction_id' => $transaction->id,
        ]);
    }

    public function test_admin_can_verify_transaction_success(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $booking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'BKG-TRX-002',
            'total_amount' => 150000,
            'status' => BookingStatus::Pending,
        ]);

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'transaction_number' => 'TRX-00200',
            'payment_method' => 'Mandiri Virtual Account',
            'amount' => 150000,
            'status' => TransactionStatus::Pending,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.transactions.verify', $transaction->id));

        $response->assertRedirect(route('admin.transactions.index'));

        $transaction->refresh();
        $this->assertEquals(TransactionStatus::Success, $transaction->status);

        $booking->refresh();
        $this->assertTrue(in_array($booking->status, [BookingStatus::PaymentCompleted, BookingStatus::Confirmed]));
    }

    public function test_admin_can_reject_transaction(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $booking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'BKG-TRX-003',
            'total_amount' => 75000,
            'status' => BookingStatus::Pending,
        ]);

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'transaction_number' => 'TRX-00300',
            'payment_method' => 'BCA Virtual Account',
            'amount' => 75000,
            'status' => TransactionStatus::Pending,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.transactions.reject', $transaction->id), [
            'notes' => 'Bukti transfer buram/tidak terbaca.',
        ]);

        $response->assertRedirect(route('admin.transactions.index'));

        $transaction->refresh();
        $this->assertEquals(TransactionStatus::Failed, $transaction->status);

        $booking->refresh();
        $this->assertEquals(BookingStatus::Cancelled, $booking->status);
    }
}
