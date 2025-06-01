<x-filament::page>
    {{-- この位置が重要：最初にオーバーレイ用 div を置く --}}
    <div
        class="fixed inset-0 flex items-center justify-center bg-white/50 pointer-events-none z-50"
    >
        <div
            wire:loading.delay
        >
            <svg
                class="animate-spin h-16 w-16 text-gray-500"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v8H4z"
                ></path>
            </svg>
        </div>
    </div>

    {{ $this->table }}
</x-filament::page>
