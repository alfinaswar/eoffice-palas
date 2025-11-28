<div class="modal fade" id="ambilBookingModal" tabindex="-1" aria-labelledby="bookingListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="bookingListModalLabel">Daftar Booking</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table datanew cell-border compact stripe" id="bookingListTable" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nomor Booking</th>
                                <th>Nama Pelanggan</th>
                                <th>Produk</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Sisa Bayar</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->Nomor ?? '-' }}</td>
                                    <td>{{ $row->getCustomer->name ?? '-' }}</td>
                                    <td>{{ $row->getProduk->Nama ?? '-' }}</td>
                                    <td>{{ $row->Tanggal ?? '-' }}</td>
                                    <td>Rp {{ number_format($row->Total ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($row->SisaBayar ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('dp.create', encrypt($row->id)) }}" class="btn btn-success">
                                            Pilih
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
