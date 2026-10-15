<div x-data="{
        isOpen: false,
        query: '',
        results: [],
        loading: false,
        search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }
            this.loading = true;
            fetch('{{ route('search.suggestions') }}?q=' + encodeURIComponent(this.query))
                .then(res => res.json())
                .then(data => {
                    this.results = data;
                    this.loading = false;
                })
                .catch(() => {
                    this.loading = false;
                });
        }
    }"
    @open-search.window="isOpen = true; $nextTick(() => $refs.searchInput.focus())"
    @keydown.escape.window="isOpen = false"
    class="relative z-50">

    <!-- Modal Backdrop -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
         @click="isOpen = false"
         style="display: none;"></div>

    <!-- Modal Dialog -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="fixed inset-x-0 top-0 max-w-2xl mx-auto p-4 sm:p-6 sm:mt-12 z-50"
         style="display: none;">

        <div class="bg-white shadow-2xl border border-zinc-100 overflow-hidden">
            <!-- Search Input Box -->
            <form action="{{ route('search') }}" method="GET" class="relative flex items-center border-b border-zinc-200 px-4">
                <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>

                <input type="text"
                       name="q"
                       x-ref="searchInput"
                       x-model="query"
                       @input.debounce.300ms="search()"
                       placeholder="Tìm kiếm sản phẩm, áo sơ mi, quần jean, blazer..."
                       class="w-full py-4 px-3 text-sm focus:outline-none border-none ring-0 focus:ring-0 text-zinc-900 placeholder-zinc-400"
                       autocomplete="off" />

                <!-- Loading indicator -->
                <div x-show="loading" class="animate-spin text-zinc-400 mr-2" style="display: none;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <button type="button" @click="isOpen = false" class="text-zinc-400 hover:text-zinc-700 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>

            <!-- Results Dropdown -->
            <div class="max-h-96 overflow-y-auto p-4 divide-y divide-zinc-100">
                <!-- If suggestions available -->
                <template x-if="results.length > 0">
                    <div>
                        <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider mb-2">Gợi ý sản phẩm</p>
                        <div class="space-y-2">
                            <template x-for="item in results" :key="item.id">
                                <a :href="item.url" class="flex items-center gap-3 p-2 hover:bg-zinc-50 transition-colors group">
                                    <img :src="item.image" :alt="item.name" class="w-12 h-14 object-cover bg-zinc-100 flex-shrink-0" />
                                    <div class="flex-grow">
                                        <h4 class="text-xs font-medium text-zinc-900 group-hover:text-black line-clamp-1" x-text="item.name"></h4>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="text-xs font-semibold text-zinc-900" x-text="item.price"></span>
                                            <template x-if="item.original_price">
                                                <span class="text-[11px] text-zinc-400 line-through" x-text="item.original_price"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-zinc-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </template>
                        </div>

                        <div class="pt-3 mt-3 text-center border-t border-zinc-100">
                            <a :href="'{{ route('search') }}?q=' + encodeURIComponent(query)" class="text-xs font-medium text-zinc-900 hover:underline">
                                Xem tất cả kết quả cho "<span x-text="query"></span>" →
                            </a>
                        </div>
                    </div>
                </template>

                <!-- No results state -->
                <template x-if="query.length >= 2 && results.length === 0 && !loading">
                    <div class="py-8 text-center text-xs text-zinc-500">
                        Không tìm thấy sản phẩm nào phù hợp với "<span class="font-medium text-zinc-800" x-text="query"></span>".
                    </div>
                </template>

                <!-- Initial popular tags -->
                <template x-if="query.length < 2">
                    <div>
                        <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider mb-2">Tìm kiếm phổ biến</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('shop', ['category' => 'ao-so-mi']) }}" class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-xs text-zinc-700 transition-colors">Áo sơ mi</a>
                            <a href="{{ route('shop', ['category' => 'ao-thun']) }}" class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-xs text-zinc-700 transition-colors">Áo thun oversize</a>
                            <a href="{{ route('shop', ['category' => 'quan-jean']) }}" class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-xs text-zinc-700 transition-colors">Quần Jean denim</a>
                            <a href="{{ route('shop', ['category' => 'ao-khoac']) }}" class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-xs text-zinc-700 transition-colors">Blazer tối giản</a>
                            <a href="{{ route('shop', ['filter' => 'sale']) }}" class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs transition-colors">Ưu đãi giảm giá</a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
