@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Catat Barang Keluar</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('outgoing.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Pilih Barang (Scan / Cari)</label>
                        <input class="form-control" list="datalistOptions" id="itemSearch" placeholder="Cari kode atau nama barang..." required onchange="fillItemInfo()">
                        <datalist id="datalistOptions">
                            @foreach ($items as $item)
                                <option value="{{ $item->sku }}" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-unit="Unit" data-stock="{{ $item->stock }}">
                                    {{ $item->name }} (Sisa: {{ $item->stock }})
                                </option>
                            @endforeach
                        </datalist>
                        <input type="hidden" name="item_id" id="item_id_hidden">

                        <div id="itemInfoPanel" class="mt-2 p-2 border rounded bg-light" style="display:none;">
                            <strong>Barang:</strong> <span id="itemNameDisplay"></span><br>
                            <strong>Sisa Stok:</strong> <span id="itemStockDisplay" class="text-danger fw-bold"></span> <span id="itemUnitDisplay"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Keluar</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan / Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Diambil oleh Divisi Produksi untuk..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger">Simpan Transaksi Keluar</button>
                    <a href="{{ route('outgoing.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function fillItemInfo() {
        var input = document.getElementById('itemSearch');
        var list = document.getElementById('datalistOptions');
        var hiddenId = document.getElementById('item_id_hidden');
        var panel = document.getElementById('itemInfoPanel');
        var nameDisplay = document.getElementById('itemNameDisplay');
        var stockDisplay = document.getElementById('itemStockDisplay');
        var unitDisplay = document.getElementById('itemUnitDisplay');

        var options = list.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].value === input.value) {
                hiddenId.value = options[i].getAttribute('data-id');
                nameDisplay.innerText = options[i].getAttribute('data-name');
                stockDisplay.innerText = options[i].getAttribute('data-stock');
                unitDisplay.innerText = options[i].getAttribute('data-unit');
                panel.style.display = 'block';
                return;
            }
        }
        hiddenId.value = '';
        panel.style.display = 'none';
    }
</script>
@endsection
