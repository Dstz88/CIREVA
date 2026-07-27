<?php

namespace App\Services;

use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Models\Report;
use Exception;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    protected ReportRepositoryInterface $reportRepository;
    protected TransactionRepositoryInterface $transactionRepository;
    protected BookingRepositoryInterface $bookingRepository;

    public function __construct(
        ReportRepositoryInterface $reportRepository,
        TransactionRepositoryInterface $transactionRepository,
        BookingRepositoryInterface $bookingRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->transactionRepository = $transactionRepository;
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Generate a summary report for transactions.
     *
     * @param string $reportType
     * @return Report
     */
    public function generateReport(string $reportType): Report
    {
        // Business logic to aggregate data could be placed here.
        // For example, calculating total successful transactions.
        
        $data = [
            'report_type' => $reportType,
            'generated_by' => Auth::id(),
            'generated_at' => now(),
        ];

        return $this->reportRepository->create($data);
    }

    /**
     * Aggregate total revenue.
     *
     * @return float
     */
    public function calculateTotalRevenue(): float
    {
        return $this->transactionRepository->getTotalSuccessfulRevenue();
    }
}
