<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title[0] ?? 'Kenaikan Kelas' }}</h5>
        </div>
        <div class="card-body">
            {{-- Tampilan Awal: Daftar Kelas --}}
            @if (!$value_kelas)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="text-align: center;">No</th>
                                <th>Nama Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($form_kelas as $index => $kelas)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td>{{ $kelas->nama }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            wire:click="processKenaikan('{{ $kelas->kode_kelas }}')">
                                            Kenaikan
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Tampilan Siswa per Kelas --}}
            @if ($value_kelas)
                <div class="mb-3">
                    <button class="btn btn-secondary btn-sm" wire:click="resetView">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </button>
                </div>
                <h5 class="mt-4">Daftar Siswa di Kelas {{ $value_kelas['nama'] }}</h5>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th>Kenaikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($value_kelas['siswa'] as $index => $siswa)
                            <tr>
                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                <td>{{ $siswa->nama }}</td>
                                <td>{{ $siswa->nisn }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm"
                                        wire:click="processStudentUpgrade({{ $siswa->id }})">
                                        Naik
                                    </button>
                                </td>
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
            <div>
                @if (session()->has('message'))
                    <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                        {!! session('message') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
