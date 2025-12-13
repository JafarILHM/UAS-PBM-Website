@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Input Barang Masuk</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('incoming.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Pilih Barang (Scan Barcode / Ketik SKU)</label>
                        <input class="form-control" list="datalistOptions" id="itemSearch" placeholder="Cari kode atau nama barang..." required onchange="fillItemID()">
                        <datalist id="datalistOptions">
                            @foreach ($items as $item)
                                <option value="{{ $item->sku }}"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-unit="{{ $item->unit->symbol ?? 'Unit' }}"> {{ $item->name }}
                                </option>
                            @endforeach
                        </datalist>
                        <input type="hidden" name="item_id" id="item_id_hidden">

                        <div id="selectedItemInfo" class="mt-2 text-info fw-bold" style="display:none;">
                            Barang Dipilih: <span id="itemNameDisplay"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Masuk</label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" min="1" required>
                                <span class="input-group-text" id="unitDisplay">Unit</span>
                            </div>
                        </div>
                    </div>

                    <div class="row p-3 bg-light rounded mb-3 border">
                        <div class="col-12"><label class="form-label fw-bold">Info Batch & Kadaluarsa (Opsional)</label></div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Batch</label>
                            <input type="text" name="batch_no" class="form-control" placeholder="Contoh: B-2025-001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Stok Masuk</button>
                    <a href="{{ route('incoming.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function fillItemID() {
        var input = document.getElementById('itemSearch');
        var list = document.getElementById('datalistOptions');
        var hiddenId = document.getElementById('item_id_hidden');
        var display = document.getElementById('selectedItemInfo');
        var nameDisplay = document.getElementById('itemNameDisplay');
        var unitDisplay = document.getElementById('unitDisplay');

        var options = list.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].value === input.value) {
                hiddenId.value = options[i].getAttribute('data-id');
                nameDisplay.innerText = options[i].getAttribute('data-name');
                unitDisplay.innerText = options[i].getAttribute('data-unit');
                display.style.display = 'block';
                return;
            }
        }
        hiddenId.value = '';
        display.style.display = 'none';
    }
</script>
@endsection
