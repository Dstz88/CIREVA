<?php

namespace App\Repositories;

use App\Repositories\Contracts\eventRepositoryInterface;

use App\Models\event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class eventRepository implements eventRepositoryInterface
{
    /**
     * Get all events.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return event::all();
    }

    /**
     * Get paginated events.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return event::paginate($perPage);
    }

    /**
     * Find an event by ID.
     *
     * @param int $id
     * @return event|null
     */
    public function findById(int $id): ?event
    {
        return event::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return event
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): event
    {
        return event::findOrFail($id);
    }

    /**
     * Create a new event.
     *
     * @param array $data
     * @return event
     */
    public function create(array $data): event
    {
        return event::create($data);
    }

    /**
     * Update an existing event.
     *
     * @param event $event
     * @param array $data
     * @return bool
     */
    public function update(event $event, array $data): bool
    {
        return $event->update($data);
    }

    /**
     * Delete an event.
     *
     * @param event $event
     * @return bool|null
     */
    public function delete(event $event): ?bool
    {
        return $event->delete();
    }

    /**
     * Search events by keyword.
     *
     * @param string $keyword
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        return event::where('title', 'like', "%{$keyword}%")
            ->orWhere('slug', 'like', "%{$keyword}%")
            ->paginate($perPage);
    }

    /**
     * Filter events dynamically.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function filter(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        // Auto-hide/exclude events created more than 7 days ago
        $query = event::with(['category', 'location'])
            ->where('created_at', '>=', now()->subDays(7));

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category'])) {
            $cat = $filters['category'];
            if (is_array($cat)) {
                $query->whereHas('category', function ($q) use ($cat) {
                    $q->whereIn('name', $cat);
                });
            } else {
                $query->whereHas('category', function ($q) use ($cat) {
                    if (is_numeric($cat)) {
                        $q->where('id', $cat);
                    } else {
                        $q->where('name', 'like', '%' . $cat . '%');
                    }
                });
            }
        } elseif (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['location'])) {
            $loc = $filters['location'];
            $query->whereHas('location', function ($q) use ($loc) {
                if (is_numeric($loc)) {
                    $q->where('id', $loc);
                } else {
                    $q->where('name', 'like', '%' . $loc . '%');
                }
            });
        } elseif (isset($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['date'])) {
            $date = $filters['date'];
            $query->whereHas('schedules', function ($sq) use ($date) {
                $sq->whereDate('start_datetime', '<=', $date)
                    ->whereDate('end_datetime', '>=', $date);
            });
        }

        if (isset($filters['organizer_profile_id'])) {
            $query->where('organizer_profile_id', $filters['organizer_profile_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Handle Sorting
        if (!empty($filters['sort'])) {
            if ($filters['sort'] === 'price_low') {
                $query->withMin('tickets', 'price')->orderBy('tickets_min_price', 'asc');
            } elseif ($filters['sort'] === 'price_high') {
                $query->withMin('tickets', 'price')->orderBy('tickets_min_price', 'desc');
            } elseif ($filters['sort'] === 'latest') {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    /**
     * Get paginated published events.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPublished(int $perPage = 15): LengthAwarePaginator
    {
        return event::published()->paginate($perPage);
    }

    /**
     * Get paginated draft events.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getDrafts(int $perPage = 15): LengthAwarePaginator
    {
        return event::draft()->paginate($perPage);
    }
}
