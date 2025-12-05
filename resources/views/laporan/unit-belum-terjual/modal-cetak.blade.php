<!-- Modal Pilih Proyek dan Format Cetak -->
<div class="modal fade" id="modalExportOmset" tabindex="-1" aria-labelledby="modalExportOmsetLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formExportOmset" method="post" target="_blank"
            action="{{ route('laporan-unit-belum-terjual.download') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExportOmsetLabel">Cetak Laporan Unit Belum Terjual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="proyek_id" class="form-label">Filter Proyek</label>
                        <select class="form-control select2" id="proyek_id" name="proyek_id">
                            <option value="">Semua Proyek</option>
                            @foreach ($proyeks as $proyek)
                                <option value="{{ $proyek->id }}">{{ $proyek->NamaProyek }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format Cetak</label>
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
