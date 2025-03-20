<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah Menu
    </x-slot>

    @method('POST')

    <div class="row">
        <!-- Parent Menu -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="menu_parent_id">Parent Menu</label>
                <select id="menu_parent_id" class="form-control" name="menu_parent_id">
                    <option value="0">Menu Utama</option>
                    @foreach ($menus as $menu)
                        @if ($menu->menu_parent_id == 0)
                            <option value="{{ $menu->id }}">{{ $menu->menu_title }}</option>
                        @endif
                    @endforeach
                </select>

            </div>
        </div>
        <!-- Nama Menu -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="menu_title">Nama Menu <span class="text-danger">*</span></label>
                <input id="menu_title" class="form-control" type="text" name="menu_title" autocomplete="off">
            </div>
        </div>

        <!-- URL Menu -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="menu_url">URL Menu</label>
                <input id="menu_url" class="form-control" type="text" name="menu_url" autocomplete="off">
            </div>
        </div>

        <!-- Target Menu -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="menu_target">Target Menu</label>
                <select id="menu_target" class="form-control" name="menu_target">
                    <option value="_self">Self</option>
                    <option value="_blank">Blank</option>
                    <option value="_parent">Parent</option>
                    <option value="_top">Top</option>
                </select>
            </div>
        </div>

        <!-- Tipe Menu -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="menu_type">Tipe Menu</label>
                <select id="menu_type" class="form-control" name="menu_type">
                    <option value="pages">Pages</option>
                    <option value="links">Links</option>
                    <option value="modules">Modules</option>
                </select>
            </div>
        </div>




        <!-- Posisi Menu -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="menu_position">Posisi Menu</label>
                <input id="menu_position" class="form-control" type="number" name="menu_position" value="0">
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i> Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i> Close
        </button>
    </x-slot>
</x-modal>
