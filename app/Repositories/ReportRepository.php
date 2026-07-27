<?php

namespace App\Repositories;

use App\Repositories\Contracts\ReportRepositoryInterface;

use App\Models\Report;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportRepository implements ReportRepositoryInterface
{
    /**
     * Get all reports.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Report::all();
    }

    /**
     * Get paginated reports.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Report::paginate($perPage);
    }

    /**
     * Find a report by ID.
     *
     * @param int $id
     * @return Report|null
     */
    public function findById(int $id): ?Report
    {
        return Report::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Report
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Report
    {
        return Report::findOrFail($id);
    }

    /**
     * Create a new report.
     *
     * @param array $data
     * @return Report
     */
    public function create(array $data): Report
    {
        return Report::create($data);
    }

    /**
     * Update an existing report.
     *
     * @param Report $report
     * @param array $data
     * @return bool
     */
    public function update(Report $report, array $data): bool
    {
        return $report->update($data);
    }

    /**
     * Delete a report.
     *
     * @param Report $report
     * @return bool|null
     */
    public function delete(Report $report): ?bool
    {
        return $report->delete();
    }

    /**
     * Find reports by type.
     *
     * @param string $reportType
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByType(string $reportType, int $perPage = 15): LengthAwarePaginator
    {
        return Report::where('report_type', $reportType)
            ->latest('generated_at')
            ->paginate($perPage);
    }

    /**
     * Find reports by the user who generated them.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByGenerator(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Report::where('generated_by', $userId)
            ->latest('generated_at')
            ->paginate($perPage);
    }

    /**
     * Get reports within a specific date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getDateRange(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator
    {
        return Report::whereBetween('generated_at', [$startDate, $endDate])
            ->latest('generated_at')
            ->paginate($perPage);
    }
}

