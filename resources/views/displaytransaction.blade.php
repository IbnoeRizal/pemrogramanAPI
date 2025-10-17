@if ($type == 'err')
    {{$message ?? "Transaksi error"}}
@else
    @switch($type)
        @case('qris')
            <x-Qrcode
                :src="$src"
                :type="$type"
                :mataUang="$mataUang"
                :rp="$Rp"
            class="qr"/>
            @break
        @default
    @endswitch
@endif
<div id="confirmation" class="d-flex flex-column align-items-center justify-content-center"></div>
<script>
    const route = Object.freeze({
        form : "{{route('form.simulation')}}",
        poll : "{{route('midtrans.statusNotif')}}",
        orderID: "{{$OrderId}}"
    });
</script>
<script src="{{ asset('js/custom.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
