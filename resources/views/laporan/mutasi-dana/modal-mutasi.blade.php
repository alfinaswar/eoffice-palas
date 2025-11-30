<!-- Modal Pilih Rentang Tanggal dan Format Cetak Mutasi Dana -->
<div class="modal fade" id="modalExportOmset" tabindex="-1" aria-labelledby="modalExportMutasiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formExportMutasi" method="post" target="_blank" action="{{ route('laporan-mutasi.download') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExportMutasiLabel">Cetak Laporan Mutasi Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required>
                        </div>
                        <div class="col">
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nama_bank_filter" class="form-label">Nama Bank</label>
                        <select class="select2" id="nama_bank_filter" name="nama_bank_filter">
                            <option value="semua">-- Semua Bank --</option>
                            @if (isset($Bank))
                                @foreach ($Bank as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->Nama }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format Cetak</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="format" id="formatExcelMutasi"
                                    value="excel" checked>
                                <label class="form-check-label" for="formatExcelMutasi">
                                    <i class="fa fa-file-excel-o text-success" aria-hidden="true"
                                        style="font-size: 1.3em;"></i>
                                    <span class="ms-1">Excel</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="format" id="formatPdfMutasi"
                                    value="pdf">
                                <label class="form-check-label" for="formatPdfMutasi">
                                    <i class="fa fa-file-pdf-o text-danger" aria-hidden="true"
                                        style="font-size: 1.3em;"></i>
                                    <span class="ms-1">PDF</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-print"></i>
                        Download</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
