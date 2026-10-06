<?php

namespace App\Livewire\Concerns;

/**
 * Use inside any Livewire 3 component:
 *
 *     use WithToast;
 *     $this->toastSuccess('تغییرات ذخیره شد');
 *
 * Methods are protected on purpose so they can't be called from the browser.
 */
/**
 * @mixin \Livewire\Component
 */
trait WithToast
{
    protected function toast(string $message, string $type = 'info', ?string $title = null, ?int $duration = null): void
    {
        $this->dispatch('toast', message: $message, type: $type, title: $title, duration: $duration);
    }

    protected function toastSuccess(string $message, ?string $title = null, ?int $duration = null): void
    {
        $this->toast($message, 'success', $title, $duration);
    }

    protected function toastError(string $message, ?string $title = null, ?int $duration = null): void
    {
        $this->toast($message, 'error', $title, $duration);
    }

    protected function toastWarning(string $message, ?string $title = null, ?int $duration = null): void
    {
        $this->toast($message, 'warning', $title, $duration);
    }

    protected function toastInfo(string $message, ?string $title = null, ?int $duration = null): void
    {
        $this->toast($message, 'info', $title, $duration);
    }
}
