<div class="container d-flex flex-column align-items-center justify-content-center p-4 rounded-4 shadow-sm bg-white"
     style="max-width:50vw; margin:auto; margin-top:60px;">

    <div class="title fs-5 fw-semibold text-dark mb-3 text-center">
        Pembayaran melalui {{ $type }}
    </div>

    <img src="{{ $src }}" alt="QRcode"
         class="img-fluid border border-2 rounded-3 p-2 bg-light"
         style="width:50%; height:50%; object-fit:contain;">

    <div class="price fs-4 fw-bold text-success mt-3">
        {{ $mataUang . " " . number_format($rp, 0, ',', '.') }}
    </div>
</div>
