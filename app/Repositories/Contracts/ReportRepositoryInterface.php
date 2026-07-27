<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Report;

interface ReportRepositoryInterface
{
    /**
     * Get all reports.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated reports.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a report by ID.
     *
     * @param int $id
     * @return Report|null
     */
    public function findById(int $id): ?Report;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Report
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Report;

    /**
     * Create a new report.
     *
     * @param array $data
     * @return Report
     */
    public function create(array $data): Report;

    /**
     * Update an existing report.
     *
     * @param Report $report
     * @param array $data
     * @return bool
     */
    public function update(Report $report, array $data): bool;

    /**
     * Delete a report.
     *
     * @param Report $report
     * @return bool|null
     */
    public function delete(Report $report): ?bool;

    /**
     * Find reports by type.
     *
     * @param string $reportType
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByType(string $reportType, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find reports by the user who generated them.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByGenerator(int $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get reports within a specific date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getDateRange(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator;
}

