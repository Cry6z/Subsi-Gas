<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DistributorList extends Component
{
    use WithPagination;

    public string $search = '';

    public array $selected = [];

    public bool $selectAll = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingPage(): void
    {
        $this->resetSelection();
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selected = $this->distributors
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->all();

            return;
        }

        $this->selected = [];
    }

    public function updatedSelected(): void
    {
        $pageIds = $this->distributors
            ->pluck('id')
            ->map(fn ($id) => (string) $id);

        if ($pageIds->isEmpty()) {
            $this->selectAll = false;
            return;
        }

        $selected = collect($this->selected)->map(fn ($id) => (string) $id);

        $this->selectAll = $pageIds->diff($selected)->isEmpty();
    }

    public function toggleActive(int $userId): void
    {
        $user = User::query()
            ->where('role', 'distributor')
            ->findOrFail($userId);

        $user->is_active = ! (bool) $user->is_active;
        $user->save();

        session()->flash('status', $user->is_active ? 'Distributor diaktifkan.' : 'Distributor dinonaktifkan.');
    }

    public function deleteDistributor(int $userId): void
    {
        $user = User::query()
            ->where('role', 'distributor')
            ->findOrFail($userId);

        $user->delete();

        $this->resetSelection();

        session()->flash('status', 'Distributor berhasil dihapus.');

        $this->resetPage();
    }

    public function deleteSelected(): void
    {
        $ids = collect($this->selected)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $users = User::query()
            ->where('role', 'distributor')
            ->whereIn('id', $ids)
            ->get();

        foreach ($users as $user) {
            $user->delete();
        }

        session()->flash('status', 'Distributor terpilih berhasil dihapus.');

        $this->resetSelection();
        $this->resetPage();
    }

    public function deleteAll(): void
    {
        $query = User::query()
            ->where('role', 'distributor')
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            });

        $users = $query->get();

        foreach ($users as $user) {
            $user->delete();
        }

        session()->flash('status', 'Semua distributor berhasil dihapus.');

        $this->resetSelection();
        $this->resetPage();
    }

    private function resetSelection(): void
    {
        $this->selectAll = false;
        $this->selected = [];
    }

    public function getDistributorsProperty()
    {
        return User::query()
            ->where('role', 'distributor')
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.distributor-list')
            ->layout('components.layouts.app', ['title' => __('Distributor Management')]);
    }
}
