 <!-- Modal Pilih Rentang Tahun dan Format Cetak -->
 <div class="modal fade" id="modalExportOmset" tabindex="-1" aria-labelledby="modalExportOmsetLabel" aria-hidden="true">
     <div class="modal-dialog">
         <form id="formExportOmset" method="post" target="_blank" action="{{ route('laporan-omset.download') }}"
             enctype="multipart/form-data">
             @csrf
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="modalExportOmsetLabel">Cetak Laporan Omset</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="mb-3 row">
                         <div class="col">
                             <label for="tahunDari" class="form-label">Dari Tahun</label>
                             <select class="select2" id="tahunDari" name="tahun_dari" required>
                                 @php
                                     $tahunSekarang = date('Y');
                                 @endphp
                                 @for ($tahun = 2010; $tahun <= $tahunSekarang; $tahun++)
                                     <option value="{{ $tahun }}">{{ $tahun }}</option>
                                 @endfor
                             </select>
                         </div>
                         <div class="col">
                             <label for="tahunSampai" class="form-label">Sampai Tahun</label>
                             <select class="select2" id="tahunSampai" name="tahun_sampai" required>
                                 @for ($tahun = 2010; $tahun <= $tahunSekarang; $tahun++)
                                     <option value="{{ $tahun }}"
                                         {{ $tahun == $tahunSekarang ? 'selected' : '' }}>
                                         {{ $tahun }}
                                     </option>
                                 @endfor
                             </select>
                         </div>
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
