@extends('layouts.app')

@section('title', 'Permintaan Akta')

@section('content')
<div class="container-fluid">
    <!-- Header Halaman Permintaan Akta -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Permintaan Akta</h3>
            <p class="text-muted mb-0">
                Daftar permintaan layanan yang siap dibuatkan akta.
            </p>
        </div>
    </div>

    <!-- Tabel Permintaan Layanan Akta -->
    <div class="card card-premium">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Client</th>
                        <th>Layanan</th>
                        <th>Tanggal Permintaan</th>
                        <th>Status</th>
                        <th>Lihat Dokumen</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Perulangan Permintaan Layanan -->
                    @forelse($permintaan as $item)
                    <tr>
                        <!-- Nomor Urut -->
                        <td>{{ $loop->iteration }}</td>
                        <!-- Nama Pemohon Client -->
                        <td>{{ $item->client->user->nama }}</td>
                        <!-- Jenis Layanan Akta -->
                        <td>{{ $item->layanan->nama_layanan }}</td>
                        <!-- Tanggal Diajukan -->
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <!-- Badge Status (Diproses) -->
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            @if($item->dokumenClient->count() > 0)
                                @if($item->dokumenClient->count() === 1)
                                    @php $singleDoc = $item->dokumenClient->first(); @endphp
                                    <a href="{{ asset('storage/' . $singleDoc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-3 py-1 px-2 fw-semibold" title="{{ $singleDoc->nama_file }}">
                                        <i class="fa-solid fa-eye me-1"></i> Lihat Dokumen
                                    </a>
                                @else
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-info dropdown-toggle rounded-3 py-1 px-2 fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-eye me-1"></i> Lihat ({{ $item->dokumenClient->count() }})
                                        </button>
                                        <ul class="dropdown-menu shadow border-0">
                                            <li class="dropdown-header small text-muted font-heading">Dokumen Upload Client:</li>
                                            @foreach($item->dokumenClient as $doc)
                                                <li>
                                                    <a class="dropdown-item small d-flex align-items-center justify-content-between py-2" href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">
                                                        <span class="text-truncate" style="max-width: 170px;" title="{{ $doc->nama_file }}"><i class="fa-solid fa-file-pdf text-danger me-2"></i>{{ $doc->nama_file }}</span>
                                                        <i class="fa-solid fa-arrow-up-right-from-square ms-2 text-muted fs-xs"></i>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @else
                                <span class="text-muted small"><i class="fa-regular fa-file me-1"></i> Belum ada file</span>
                            @endif
                        </td>
                        <!-- Tombol Aksi Buat Akta -->
                        <td class="text-center">
                            <a href="{{ route('admin.akta.create', $item->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-file-signature"></i> Buat Akta
                            </a>
                        </td>
                    </tr>
                    @empty
                    <!-- Tampilan Jika Belum Ada Permintaan Layanan yang Siap -->
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            Belum ada permintaan akta.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection