<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah Data
    </x-slot>

    @method('POST')
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="tanggal" data-target-input="nearest">
                    <input type="text" name="tanggal" class="form-control datetimepicker-input"
                        data-target="#tanggal" data-toggle="datetimepicker" autocomplete="off" />
                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="my-input">Kelas</label>
                <select name="rombel_id" id="rombel_id" class="form-control">
                    <option value="">Pilih salah satu</option>
                    @foreach ($rombel as $item)
                        <option value="{{ $item->id }}">{{ $item->kelas->nama }} {{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="mata_pelajaran">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" id="mata_pelajaran" class="form-control select2">
                    <option value="">Pilih Mata Pelajaran</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="tujuan_pembelajaran">Tujuan Pembelajaran</label>
                <textarea id="tujuan_pembelajaran" class="form-control" name="tujuan_pembelajaran" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="materi">Materi</label>
                <textarea id="materi" class="form-control" name="materi" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="penilaian">Penilaian</label>
                <textarea id="penilaian" class="form-control" name="penilaian" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="metode_pembelajaran">Metode Pembelajaran</label>
                <textarea id="metode_pembelajaran" class="form-control" name="metode_pembelajaran" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="evaluasi">Evaluasi Pembelajaran</label>
                <textarea id="evaluasi" class="form-control" name="evaluasi" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="refleksi">Refleksi Pembelajaran</label>
                <textarea id="refleksi" class="form-control" name="refleksi" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="tugas">Tugas Siswa</label>
                <textarea id="tugas" class="form-control" name="tugas" rows="3"></textarea>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status"
                aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>

{{--  @push('scripts')
    <script>
        $(document).ready(function() {

            $('#mata_pelajaran').empty().prop('disabled', true);
        });
    </script>
@endpush  --}}
