@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6 text-xs sm:text-sm text-zinc-600 leading-relaxed">
    <x-breadcrumb :items="[['label' => 'Chính Sách Bảo Mật']]" />

    <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950 pb-4 border-b border-zinc-200">
        Chính Sách Bảo Mật Thông Tin
    </h1>

    <p>
        NOVAWEAR cam kết bảo vệ sự riêng tư và bảo mật thông tin cá nhân của quý khách hàng khi truy cập và mua sắm trên hệ thống thương mại điện tử của chúng tôi.
    </p>

    <h2 class="text-base font-bold text-zinc-950 uppercase pt-2">1. Thu Thập Thông Tin Cá Nhân</h2>
    <p>
        Khi quý khách tạo tài khoản hoặc đặt hàng tại website, chúng tôi thu thập các thông tin bao gồm: Họ tên, số điện thoại, địa chỉ email, địa chỉ nhận hàng nhằm phục vụ công tác xử lý đơn hàng và giao nhận hàng hóa.
    </p>

    <h2 class="text-base font-bold text-zinc-950 uppercase pt-2">2. Sử Dụng Thông Tin</h2>
    <p>
        Thông tin thu thập chỉ được sử dụng cho việc xử lý đơn hàng, thông báo trạng thái vận chuyển, chăm sóc khách hàng và gửi các thông tin ưu đãi khi quý khách đăng ký nhận bản tin.
    </p>

    <h2 class="text-base font-bold text-zinc-950 uppercase pt-2">3. Cam Kết Bảo Mật</h2>
    <p>
        Chúng tôi tuyệt đối không bán, chia sẻ hoặc chuyển giao thông tin cá nhân của quý khách cho bên thứ ba vì mục đích thương mại, ngoại trừ các đơn vị vận chuyển đối tác cần thiết để hoàn tất việc giao hàng.
    </p>
</div>
@endsection
