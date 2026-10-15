<footer class="bg-zinc-950 text-white pt-16 pb-12 border-t border-zinc-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8 pb-12 border-b border-zinc-900">

            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-4">
                <a href="{{ route('home') }}" class="inline-block">
                    <span class="text-2xl font-extrabold tracking-[0.25em] text-white uppercase">NOVAWEAR</span>
                </a>
                <p class="text-xs text-zinc-400 leading-relaxed max-w-sm">
                    Thương hiệu thời trang đương đại với triết lý thiết kế tối giản, tập trung vào phom dáng chỉn chu và trải nghiệm xúc giác từ các chất liệu tự nhiên thượng hạng.
                </p>
                <div class="text-xs text-zinc-400 space-y-1.5 pt-2">
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>68 Nguyễn Huệ, Bến Nghé, Quận 1, TP. Hồ Chí Minh</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>Hotline: 1900 888 999 (8:30 - 22:00)</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Email: contact@novawear.vn</span>
                    </p>
                </div>
            </div>

            <!-- Shop Links -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-200">Mua Sắm</h4>
                <ul class="space-y-2 text-xs text-zinc-400">
                    <li><a href="{{ route('shop') }}" class="hover:text-white transition-colors">Tất cả sản phẩm</a></li>
                    <li><a href="{{ route('category.show', 'ao-so-mi') }}" class="hover:text-white transition-colors">Áo sơ mi</a></li>
                    <li><a href="{{ route('category.show', 'ao-thun') }}" class="hover:text-white transition-colors">Áo thun oversize</a></li>
                    <li><a href="{{ route('category.show', 'quan-jean') }}" class="hover:text-white transition-colors">Quần Jean denim</a></li>
                    <li><a href="{{ route('category.show', 'ao-khoac') }}" class="hover:text-white transition-colors">Áo khoác & Blazer</a></li>
                    <li><a href="{{ route('shop', ['filter' => 'sale']) }}" class="text-rose-400 hover:text-rose-300 font-medium transition-colors">Ưu đãi giảm giá</a></li>
                </ul>
            </div>

            <!-- Customer Service Links -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-200">Hỗ Trợ</h4>
                <ul class="space-y-2 text-xs text-zinc-400">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Về NOVAWEAR</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Liên hệ tư vấn</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Chính sách bảo mật</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-white transition-colors">Điều khoản dịch vụ</a></li>
                    <li><a href="{{ route('account.orders') }}" class="hover:text-white transition-colors">Tra cứu đơn hàng</a></li>
                </ul>
            </div>

            <!-- Newsletter Box -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-200">Bản Tin</h4>
                <p class="text-xs text-zinc-400 leading-relaxed">
                    Đăng ký để nhận sớm nhất thông tin về các bộ sưu tập giới hạn và mã giảm giá đặc quyền.
                </p>
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã đăng ký nhận bản tin của NOVAWEAR!');" class="space-y-2">
                    <div class="relative">
                        <input type="email"
                               required
                               placeholder="Địa chỉ email của bạn"
                               class="w-full bg-zinc-900 border border-zinc-800 text-white placeholder-zinc-500 text-xs px-3.5 py-2.5 focus:outline-none focus:border-zinc-500 transition-colors" />
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 bg-white text-zinc-950 text-xs font-bold uppercase tracking-wider hover:bg-zinc-200 transition-colors">
                        Đăng Ký
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500 gap-4">
            <p>© {{ date('Y') }} NOVAWEAR. Tất cả quyền được bảo lưu. Thiết kế thương mại điện tử hiện đại.</p>
            <div class="flex items-center space-x-6">
                <span class="hover:text-zinc-300">Thanh toán an toàn COD & Chuyển khoản</span>
                <span>•</span>
                <span class="hover:text-zinc-300">Giao hàng 63 tỉnh thành</span>
            </div>
        </div>
    </div>
</footer>
