@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6 text-xs sm:text-sm text-zinc-600 leading-relaxed">
    <x-breadcrumb :items="[['label' => 'Điều Khoản Dịch Vụ']]" />

    <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950 pb-4 border-b border-zinc-200">
        Điều Khoản Dịch Vụ
    </h1>

    <h2 class="text-base font-bold text-zinc-950 uppercase pt-2">1. Điều Khoản Chung</h2>
    <p>
        Khi truy cập hoặc mua sắm tại NOVAWEAR, quý khách mặc nhiên đồng ý tuân thủ các điều khoản, quy định và hướng dẫn của website. Chúng tôi có quyền thay đổi hoặc cập nhật điều khoản bất kỳ lúc nào để phù hợp với quy định pháp luật.
    </p>

    <h2 class="text-base font-bold text-zinc-950 uppercase pt-2">2. Đặt Hàng & Giá Cả</h2>
    <p>
        Tất cả các mức giá niêm yết trên website đều là giá thanh toán cuối cùng bằng Việt Nam Đồng (VND). NOVAWEAR cam kết cung cấp thông tin sản phẩm và giá cả chính xác nhất. Trong trường hợp xảy ra lỗi hiển thị giá ngoài ý muốn, chúng tôi sẽ liên hệ để thông báo hướng giải quyết trước khi giao hàng.
    </p>

    <h2 class="text-base font-bold text-zinc-950 uppercase pt-2">3. Chính Sách Đổi Trả Hàng</h2>
    <p>
        Khách hàng được quyền đổi size hoặc đổi sang mẫu khác trong vòng 07 ngày kể từ ngày nhận hàng, với điều kiện sản phẩm còn nguyên tem mác, chưa qua giặt ủi hoặc sử dụng.
    </p>
</div>
@endsection
