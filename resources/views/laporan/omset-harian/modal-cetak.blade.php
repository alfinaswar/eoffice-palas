<!-- Modal Pilih Rentang Tanggal dan Jenis Cetak -->
<div class="modal fade" id="modalExportOmset" tabindex="-1" aria-labelledby="modalExportOmsetLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formExportOmsetHarian" method="post" target="_blank"
            action="{{ route('laporan-omset-harian.download') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExportOmsetLabel">Cetak Laporan Omset Harian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="tanggalDari" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="tanggalDari" name="tanggal_dari"
                                value="{{ date('Y-m-01') }}" required />
                        </div>
                        <div class="col">
                            <label for="tanggalSampai" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="tanggalSampai" name="tanggal_sampai"
                                value="{{ date('Y-m-d') }}" required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Cetak</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="format" id="formatExcel"
                                    value="excel" checked>
                                <label class="form-check-label" for="formatExcel">
                                    <i class="fa fa-file-excel-o text-success" aria-hidden="true"
                                        style="font-size: 1.3em;"></i>
                                    <span class="ms-1">Excel</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="format" id="formatPdf"
                                    value="pdf">
                                <label class="form-check-label" for="formatPdf">
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
