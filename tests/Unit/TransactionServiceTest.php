<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TransactionService;
use App\Models\Transaction;
use App\Models\Booking;
use App\Enums\TransactionStatus;
use App\Enums\BookingStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TransactionService $transactionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionService = app(TransactionService::class);
    }

    public function test_can_create_transaction()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Pending->value]);

        $transaction = $this->transactionService->createTransaction($booking->id, 'Bank Transfer', 100000);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals(TransactionStatus::Pending, $transaction->status);
        $this->assertEquals(100000, $transaction->amount);
    }

    public function test_can_verify_success_transaction()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Pending->value]);
        $transaction = Transaction::factory()->create([
            'booking_id' => $booking->id,
            'status' => TransactionStatus::Pending->value,
            'amount' => $booking->total_amount
        ]);

        $this->transactionService->verifySuccess($transaction->id);

        $transaction->refresh();
        $booking->refresh();

        $this->assertEquals(TransactionStatus::Success, $transaction->status);
        // After verifySuccess, booking status becomes PaymentCompleted per state machine
        $this->assertEquals(BookingStatus::PaymentCompleted, $booking->status);
    }

    public function test_can_mark_transaction_as_failed()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Pending->value]);
        $transaction = Transaction::factory()->create([
            'booking_id' => $booking->id,
            'status' => TransactionStatus::Pending->value,
            'amount' => $booking->total_amount
        ]);

        $this->transactionService->markAsFailed($transaction->id);

        $transaction->refresh();
        $this->assertEquals(TransactionStatus::Failed, $transaction->status);
    }
}
