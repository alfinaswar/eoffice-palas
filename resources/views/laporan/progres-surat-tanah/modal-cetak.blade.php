 <!-- Modal Pilih Rentang Tahun dan Cetak PDF -->
 <div class="modal fade" id="modalExportOmset" tabindex="-1" aria-labelledby="modalExportOmsetLabel" aria-hidden="true">
     <div class="modal-dialog">
         <form id="formExportOmset" method="post" target="_blank"
             action="{{ route('laporan-progres-surat-tanah.download') }}" enctype="multipart/form-data">
             @csrf
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="modalExportOmsetLabel">Cetak Laporan Progres Surat Tanah</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="mb-3 row">
                         <div class="col">
                             <label for="Proyek" class="form-label">Nama Proyek</label>
                             <select class="select2" id="Proyek" name="proyek_id" required>
                                 <option value="semua">Semua Proyek</option>
                                 @foreach ($proyeks as $p)
                                     <option value="{{ $p->id }}">{{ $p->NamaProyek }}</option>
                                 @endforeach
                             </select>
                         </div>
                     </div>
                     <!-- Tanggal Mulai dan Tanggal Akhir -->
                     <div class="mb-3 row">
                         <div class="col-6">
                             <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                             <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                                 required>
                         </div>
                         <div class="col-6">
                             <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                             <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                                 required>
                         </div>
                     </div>
                     <!-- Hanya cetak PDF, tidak ada pilihan format -->
                     <input type="hidden" name="format" value="pdf">
                 </div>
                 <div class="modal-footer">
                     <button type="submit" class="btn btn-success"><i class="fa fa-print"></i>
                         Download PDF</button>
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                 </div>
             </div>
         </form>
     </div>
 </div>
