<x-filament-panels::layout.base :livewire="$this">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 font-sans antialiased">

        {{-- Background Blobs --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-blue-600/30 blur-[120px] animate-pulse"></div>
            <div class="absolute top-[20%] -right-[10%] w-[50%] h-[50%] rounded-full bg-indigo-600/20 blur-[150px] animate-pulse" style="animation-delay: 2s"></div>
            <div class="absolute -bottom-[10%] left-[20%] w-[45%] h-[45%] rounded-full bg-purple-600/25 blur-[130px] animate-pulse" style="animation-delay: 4s"></div>
        </div>

        {{-- Card --}}
        <div class="relative z-10 w-full max-w-[460px] px-6 py-12">
            <div class="relative overflow-hidden rounded-[2.5rem] border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-2xl sm:p-12">

                {{-- Logo --}}
                <div class="relative mb-10 flex flex-col items-center text-center">
                    <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 p-4 shadow-xl shadow-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h1 class="mb-2 text-4xl font-black tracking-tight text-white">
                        Lesgo <span class="bg-gradient-to-r from-blue-400 to-indigo-300 bg-clip-text text-transparent">Admin</span>
                    </h1>
                    <p class="text-sm font-medium tracking-wide text-slate-400 uppercase">Premium Logistics Suite</p>
                </div>

                {{-- Welcome --}}
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
                    <p class="mt-1 text-slate-400">Please authenticate to continue.</p>
                </div>

                {{-- Form --}}
                <x-filament-panels::form wire:submit="authenticate">
                    {{ $this->form }}
                    <div class="mt-6">
                        <x-filament-panels::form.actions
                            :actions="$this->getCachedFormActions()"
                            :full-width="true"
                        />
                    </div>
                </x-filament-panels::form>

                {{-- Footer --}}
                <div class="mt-10 text-center">
                    <p class="text-[10px] font-bold tracking-[0.2em] text-slate-500 uppercase">
                        &copy; {{ date('Y') }} Lesgo Logistics &bull; System Verified
                    </p>
                </div>

            </div>
        </div>

    </div>
</x-filament-panels::layout.base>
