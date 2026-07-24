@extends('layouts.app')

@section('title','Permintaan Akta')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">Permintaan Akta</h3>
            <p class="text-muted mb-0">
                Daftar permintaan layanan yang siap dibuatkan akta.
            </p>
        </div>

    </div>

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

                        <th class="text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($permintaan as $item)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $item->client->user->nama }}</td>

                        <td>{{ $item->layanan->nama_layanan }}</td>

                        <td>{{ $item->created_at->format('d-m-Y') }}</td>

                        <td>

                            <span class="badge bg-warning text-dark">

                                {{ $item->status }}

                            </span>

                        </td>

                        <td class="text-center">

                            <a href="{{ route('admin.akta.create',$item->id) }}"
                                class="btn btn-primary btn-sm">

                                <i class="fa fa-file-signature"></i>

                                Buat Akta

                            </a>

                        </td>

                    </tr>

                    @empty

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