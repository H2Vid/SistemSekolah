<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title[0] ?? '' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <x-mazer-header />
                <table class="table table-hover table-striped" style="width:100%; margin-top: 30px;">
                    <thead>
                        <tr>
                            <x-mazer-loop-th :$searchBy :$orderBy :$order />
                            @if ($this->akses_update == 1 || $this->akses_delete == 1)
                                <th style="width: 8%!important; text-align: center;">
                                    Aksi
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($get as $d)
                            <tr>
                                <td>{{ $d->nip }}</td>
                                <td>{{ $d->kode_kelas }}</td>
                                <td>{{ $d->tingkat_kelas }}</td>
                                <td>{{ $d->nama }}</td>
                                @if ($this->akses_update == 1 || $this->akses_delete == 1)
                                    <td style="text-align: right; white-space:nowrap;">
                                        @if ($this->akses_update == 1)
                                            <button title="Ubah" class="btn btn-warning"
                                                wire:click="loadStudents('{{ $d->kode_kelas }}')"
                                                @click="$nextTick(() => new bootstrap.Modal(document.getElementById('mazer-modal')).show())">
                                                <i class="align-middle" data-feather="edit"></i> Ubah Semester
                                            </button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100" class="text-center">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="float-end">
                    {{ $get->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Create / Update Modal --}}
    <x-mazer-modal title="{{ $isUpdate ? 'Ubah' : 'Tambah' }} {{ $title[0] }}">
        <x-mazer-form submit="save">
            <div class="col-md-12 border mb-3">
                @if ($value_kelas)
                    <h5 class="mt-4">Daftar Siswa di Kelas {{ $value_kelas['nama'] }}</h5>
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="text-align: center;">No</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($value_kelas['siswa'] as $index => $siswa)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->nisn }}</td>
                                    <td>{{ $siswa->semester->nama }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center;">
                                        Tidak ada siswa di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Update Semester Siswa</label>
                    <x-mazer-input type="select" name="form.id" id="id">
                        <option value="">--Pilih Semester--</option>
                        @foreach ($semester as $s)
                            <option value="{{ $s->id }}">{{ $s->id }} - {{ $s->nama }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.id" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
</div>
<script>
    window.addEventListener('notification-failed', event => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'warning',
            title: "Semester gagal diupdate. Terimakasih"
        })
    })
    window.addEventListener('notification-success', event => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: "Semester berhasil diupdate. Terimakasih"
        })
    })
</script>
