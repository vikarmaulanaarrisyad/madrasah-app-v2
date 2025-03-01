<x-modal data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="form-group row">
        <label for="jenis_kompetensi" class="col-sm-3 col-form-label">Jenis Kompetensi</label>
        <div class="col-sm-9">
            <div class="form-check form-check-inline">
                <input class="form-check-input @error('jenis_kompetensi') is-invalid @enderror" type="radio"
                    name="jenis_kompetensi" id="spiritual" value="1"
                    {{ old('jenis_kompetensi') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="spiritual">Spiritual</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input @error('jenis_kompetensi') is-invalid @enderror" type="radio"
                    name="jenis_kompetensi" id="sosial" value="2"
                    {{ old('jenis_kompetensi') == '2' ? 'checked' : '' }}>
                <label class="form-check-label" for="sosial">Sosial</label>
            </div>

            <!-- Pesan Error Ditempatkan di Bawah -->
            @error('jenis_kompetensi')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="kode" class="col-sm-3 col-form-label">Kode Butir Sikap</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="kode" value="{{ old('kode') }}">
        </div>
    </div>
    <div class="form-group row">
        <label for="butir_sikap" class="col-sm-3 col-form-label">Butir Sikap</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="butir_sikap" rows="2">{{ old('butir_sikap') }}</textarea>
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan</button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
