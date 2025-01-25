<div>
    @if(session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('danger'))
        <div id="danger-alert" class="alert alert-danger">
            {{ session('danger') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="">Data Akun</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/') }}/cms/profile/simpanProfile" method="post" enctype="multipart/form-data">
                    @csrf
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{ asset('storage/siswa/' . $image) }}" style="width: 100%;">
                        <div class="mb-3">
                            <label class="form-label">Foto</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">NISN</label>
                            <input type="text" class="form-control" name="nisn" value="{{$nisn}}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" value="{{$nama}}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir" value="{{$tanggal_lahir}}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" value="{{$tempat_lahir}}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                <option value="">--Pilih Jenis Kelamin--</option>
                                <option value="Laki-laki" @if($jenis_kelamin == 'Laki-laki') selected @endif>Laki-laki</option>
                                <option value="Perempuan" @if($jenis_kelamin == 'Perempuan') selected @endif>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Agama</label>
                            <select class="form-control" name="agama" id="agama">
                                <option value="">--Pilih Agama--</option>
                                <option value="Islam" @if($agama == 'Islam') selected @endif>Islam</option>
                                <option value="Kristen" @if($agama == 'Kristen') selected @endif>Kristen</option>
                                <option value="Hindu" @if($agama == 'Hindu') selected @endif>Hindu</option>
                                <option value="Budha" @if($agama == 'Budha') selected @endif>Budha</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" class="form-control" name="no_hp" value="{{$no_hp}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" value="{{$email}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Wali</label>
                            <input type="text" class="form-control" name="nama_wali" value="{{$nama_wali}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No. Telpon Wali</label>
                            <input type="text" class="form-control" name="no_telepon_wali" value="{{$no_telepon_wali}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" placeholder="Alamat">{{$alamat}}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="float-end">
                            <button
                                class="btn btn-success"
                                id="form-add"
                                wire:loading.attr="disabled"
                                wire:target="saveWithUpload"
                                type="submit">
                                <i class="align-middle" data-feather="save"></i>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
