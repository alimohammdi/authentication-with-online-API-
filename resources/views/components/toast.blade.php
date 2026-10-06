{{--
    <x-toast /> : reusable toast for Laravel + Livewire 3 (no dependencies, RTL-ready)

    Props:
      position : top-left | top-right | top-center | bottom-left | bottom-right | bottom-center
      duration : default auto-dismiss time in ms (0 = stays until closed)
      max      : max number of toasts visible at once
      dir      : rtl | ltr
--}}
@props([
    'position' => 'top-left',
    'duration' => 5000,
    'max' => 4,
    'dir' => 'rtl',
])

@once
<style>
.toast-stack {
    --toast-shift: -14px;
    --t-bg: #ffffff;
    --t-fg: #14181f;
    --t-mute: #566072;
    --t-line: rgba(20, 24, 31, .1);
    --t-shadow: 0 1px 2px rgba(20, 24, 31, .06), 0 12px 28px -10px rgba(20, 24, 31, .25);
    position: fixed;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: .625rem;
    width: min(100vw, 25rem);
    box-sizing: border-box;
    padding: max(1rem, env(safe-area-inset-top)) 1rem 1rem;
    pointer-events: none;
}
.dark .toast-stack,
[data-theme="dark"] .toast-stack {
    --t-bg: #1b2029;
    --t-fg: #eef1f6;
    --t-mute: #a3adbd;
    --t-line: rgba(255, 255, 255, .1);
    --t-shadow: 0 1px 2px rgba(0, 0, 0, .4), 0 14px 32px -10px rgba(0, 0, 0, .6);
}

.toast-top-left      { top: 0; left: 0; }
.toast-top-right     { top: 0; right: 0; }
.toast-top-center    { top: 0; left: 50%; transform: translateX(-50%); }
.toast-bottom-left   { bottom: 0; left: 0; flex-direction: column-reverse; --toast-shift: 14px; }
.toast-bottom-right  { bottom: 0; right: 0; flex-direction: column-reverse; --toast-shift: 14px; }
.toast-bottom-center { bottom: 0; left: 50%; transform: translateX(-50%); flex-direction: column-reverse; --toast-shift: 14px; }

.toast {
    pointer-events: auto;
    display: flex;
    align-items: center;
    gap: .8rem;
    padding: .7rem .85rem;
    background: var(--t-bg);
    color: var(--t-fg);
    border: 1px solid var(--t-line);
    border-radius: 14px;
    box-shadow: var(--t-shadow);
    font-family: inherit;
}
.toast[data-type="success"] { --c: #0d9373; }
.toast[data-type="error"]   { --c: #e5484d; }
.toast[data-type="warning"] { --c: #d98a06; }
.toast[data-type="info"]    { --c: #3e63dd; }

/* Icon with a ring that drains while the toast is alive */
.toast-ring {
    position: relative;
    flex: none;
    width: 2.5rem;
    height: 2.5rem;
    display: grid;
    place-items: center;
    color: var(--c);
}
.toast-ring::before {
    content: "";
    position: absolute;
    inset: .3rem;
    border-radius: 50%;
    background: var(--c);
    opacity: .12;
}
.toast-arc {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
    fill: none;
    stroke: currentColor;
    stroke-width: 2.25;
    stroke-linecap: round;
}
.toast-track { opacity: .16; }
.toast-timer {
    stroke-dasharray: 113.1;
    stroke-dashoffset: 0;
    animation: toast-ring linear forwards;
}
@keyframes toast-ring { to { stroke-dashoffset: 113.1; } }

.toast-ico {
    position: relative;
    display: grid;
    place-items: center;
    width: 1.25rem;
    height: 1.25rem;
}
.toast-ico svg {
    width: 100%;
    height: 100%;
    fill: none;
    stroke: currentColor;
    stroke-width: 2.2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.toast-body { flex: 1; min-width: 0; }
.toast-title { margin: 0; font-size: .9rem; font-weight: 700; line-height: 1.6; }
.toast-msg {
    margin: 0;
    font-size: .85rem;
    line-height: 1.75;
    color: var(--t-mute);
    overflow-wrap: anywhere;
}
.toast-body:not(.has-title) .toast-msg {
    font-size: .9rem;
    font-weight: 600;
    color: var(--t-fg);
}

.toast-close {
    flex: none;
    width: 1.75rem;
    height: 1.75rem;
    display: grid;
    place-items: center;
    padding: 0;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--t-mute);
    cursor: pointer;
}
.toast-close:hover { background: var(--t-line); color: var(--t-fg); }
.toast-close:focus-visible { outline: 2px solid var(--c); outline-offset: 1px; }
.toast-close svg {
    width: .95rem;
    height: .95rem;
    fill: none;
    stroke: currentColor;
    stroke-width: 2.2;
    stroke-linecap: round;
}

.toast-tr  { transition: opacity .25s ease, transform .38s cubic-bezier(.2, 1.1, .3, 1); }
.toast-out { opacity: 0; transform: translateY(var(--toast-shift)) scale(.96); }
.toast-in  { opacity: 1; transform: none; }

@media (prefers-reduced-motion: reduce) {
    .toast-tr { transition-duration: .01ms; }
    .toast-timer { animation: none; }
}
</style>

<script>
(() => {
    const icons = {
        success: '<svg viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>',
        error: '<svg viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>',
        warning: '<svg viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg>',
        info: '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></svg>',
    };

    const register = () => {
        Alpine.data('toastStack', (cfg = {}) => {
            const timers = {};

            return {
                items: [],
                seq: 0,

                push(raw) {
                    const d = Array.isArray(raw) ? raw[0] : raw;
                    if (!d || !d.message) return;

                    const duration = d.duration == null ? cfg.duration : Number(d.duration);

                    this.items.push({
                        id: ++this.seq,
                        type: icons[d.type] ? d.type : 'info',
                        title: d.title || '',
                        message: String(d.message),
                        duration,
                        remaining: duration,
                        startedAt: 0,
                        paused: false,
                    });

                    const t = this.items[this.items.length - 1];
                    while (this.items.length > cfg.max) this.dismiss(this.items[0].id);
                    this.start(t);
                },

                start(t) {
                    if (t.duration <= 0) return;
                    t.startedAt = Date.now();
                    timers[t.id] = setTimeout(() => this.dismiss(t.id), t.remaining);
                },

                pause(t) {
                    if (t.duration <= 0 || t.paused) return;
                    clearTimeout(timers[t.id]);
                    t.remaining -= Date.now() - t.startedAt;
                    t.paused = true;
                },

                resume(t) {
                    if (t.duration <= 0 || !t.paused) return;
                    t.paused = false;
                    this.start(t);
                },

                dismiss(id) {
                    clearTimeout(timers[id]);
                    delete timers[id];
                    this.items = this.items.filter((i) => i.id !== id);
                },

                icon(type) {
                    return icons[type];
                },
            };
        });
    };

    if (window.Alpine) register();
    else document.addEventListener('alpine:init', register);

    // Global JS helper: toast('...'), toast.success('...'), toast.error('...', { title: '...' })
    if (!window.toast) {
        const fire = (type) => (message, opts = {}) =>
            window.dispatchEvent(new CustomEvent('toast', { detail: { message, type, ...opts } }));

        window.toast = Object.assign(fire('info'), {
            success: fire('success'),
            error: fire('error'),
            warning: fire('warning'),
            info: fire('info'),
        });
    }
})();
</script>
@endonce

<div
    class="toast-stack toast-{{ $position }}"
    dir="{{ $dir }}"
    x-data="toastStack({ duration: {{ (int) $duration }}, max: {{ max((int) $max, 1) }} })"
    x-on:toast.window="push($event.detail)"
    @if (session()->has('toast'))
        x-init="$nextTick(() => push(@js(session('toast'))))"
    @endif
>
    <template x-for="t in items" :key="t.id">
        <div
            class="toast"
            :data-type="t.type"
            :role="t.type === 'error' || t.type === 'warning' ? 'alert' : 'status'"
            x-transition:enter="toast-tr"
            x-transition:enter-start="toast-out"
            x-transition:enter-end="toast-in"
            x-transition:leave="toast-tr"
            x-transition:leave-start="toast-in"
            x-transition:leave-end="toast-out"
            x-on:mouseenter="pause(t)"
            x-on:mouseleave="resume(t)"
            x-on:focusin="pause(t)"
            x-on:focusout="resume(t)"
        >
            <div class="toast-ring">
                <svg class="toast-arc" viewBox="0 0 40 40" aria-hidden="true">
                    <circle class="toast-track" cx="20" cy="20" r="18"/>
                    <circle
                        class="toast-timer"
                        cx="20" cy="20" r="18"
                        x-show="t.duration > 0"
                        :style="{ animationDuration: t.duration + 'ms', animationPlayState: t.paused ? 'paused' : 'running' }"
                    />
                </svg>
                <span class="toast-ico" aria-hidden="true" x-html="icon(t.type)"></span>
            </div>

            <div class="toast-body" :class="{ 'has-title': t.title }">
                <p class="toast-title" x-show="t.title" x-text="t.title"></p>
                <p class="toast-msg" x-text="t.message"></p>
            </div>

            <button type="button" class="toast-close" aria-label="بستن" x-on:click="dismiss(t.id)">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>
