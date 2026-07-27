<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Booking;
use App\Models\Transaction;
use App\Services\TransactionService;

class AdminTransactionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_verify_transaction_via_service()
    {
        // Test the core workflow through the service layer directly
        $booking = Booking::factory()->create(['status' => \App\Enums\BookingStatus::Pending->value]);
        $transaction = Transaction::factory()->create([
            'booking_id' => $booking->id,
            'status' => \App\Enums\TransactionStatus::Pending->value,
        ]);

        $service = app(TransactionService::class);
        $service->verifySuccess($transaction->id);
        
        $transaction->refresh();
        $booking->refresh();

        $this->assertEquals(\App\Enums\TransactionStatus::Success, $transaction->status);
        $this->assertEquals(\App\Enums\BookingStatus::PaymentCompleted, $booking->status);
    }

    public function test_admin_verify_route_requires_authentication()
    {
        $booking = Booking::factory()->create(['status' => \App\Enums\BookingStatus::Pending->value]);
        $transaction = Transaction::factory()->create([
            'booking_id' => $booking->id,
            'status' => \App\Enums\TransactionStatus::Pending->value,
        ]);

        // Unauthenticated access should redirect to login
        $response = $this->put("/admin/transactions/{$transaction->id}/verify", ['is_success' => 1]);
        $response->assertRedirect('/login');
    }
}
