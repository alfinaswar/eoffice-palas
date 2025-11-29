<div class="modal fade" id="modalBayar" tabindex="-1" aria-labelledby="modalBayarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formBayar" method="POST" action="{{ route('transaksi.bayar') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalBayarLabel">
                        <i data-feather="credit-card" class="me-2"></i>Pembayaran Tagihan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 mb-3">
                            <label for="dibayar_oleh" class="form-label">Dibayar Oleh</label>
                            <div class="input-group">
                                <span class="input-group-text"><i data-feather="user"></i></span>
                                <input type="text" class="form-control" placeholder="Nama Pembayar" id="dibayar_oleh"
                                    name="DibayarOleh" required>
                            </div>
                        </div>
                        <!-- INFO REKENING PEMBAYAR -->
                        <div class="col-md-6 mb-3">
                            <label for="dari_bank" class="form-label">Rekening Pembayar - Nama Bank</label>
                            <div class="input-group">
                                <span class="input-group-text"><i data-feather="credit-card"></i></span>
                                <select class="form-select select2" id="dari_bank" name="DariBank" style="width: 100%;"
                                    data-placeholder="Pilih Nama Bank Pembayar">
                                    <option value="">Pilih Nama Bank Pembayar</option>
                                    @if (isset($bank) && $bank->count())
                                        @foreach ($bank as $b)
                                            <option value="{{ $b->id }}">{{ $b->Nama }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_rekening" class="form-label">Rekening Pembayar - No. Rekening</label>
                            <div class="input-group">
                                <span class="input-group-text"><i data-feather="hash"></i></span>
                                <input type="text" class="form-control" placeholder="Nomor Rekening" id="no_rekening"
                                    name="NoRekening">
                            </div>
                        </div>
                        <!-- END INFO REKENING PEMBAYAR -->

                        <div class="col-md-6 mb-3">
                            <label for="input-cicilan-ke" class="form-label">Pembayaran Ke</label>
                            <div class="input-group">
                                <span class="input-group-text"><i data-feather="hash"></i></span>
                                <input type="text" class="form-control bg-light" name="CicilanKe"
                                    id="input-cicilan-ke" readonly placeholder="-" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="input-besar-cicilan" class="form-label">Besar Cicilan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control bg-light" name="BesarCicilan"
                                    id="input-besar-cicilan" readonly placeholder="-" value="">
                            </div>
                        </div>

                        <!-- INFO REKENING PENERIMA -->
                        <div class="col-12 mb-3 mt-2">
                            <label class="form-label">Rekening Penerima</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                                        <select class="form-select select2" name="NamaBank" id="nama_bank_select"
                                            style="width: 100%;" data-placeholder="Pilih Nama Bank Penerima"
                                            onchange="updateNoRekeningPenerima()">
                                            <option value="">Pilih Nama Bank Penerima</option>
                                            @if (isset($bank) && $bank->count())
                                                @foreach ($bank as $b)
                                                    <option value="{{ $b->id }}"
                                                        data-norek="{{ $b->Rekening ?? '' }}">
                                                        {{ $b->Nama }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i data-feather="hash"></i></span>
                                        <input type="text" class="form-control"
                                            placeholder="No. Rekening Penerima" name="NoRekeningPenerima"
                                            id="no_rekening_penerima" readonly>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- END INFO REKENING PENERIMA -->

                    </div>
                    <input type="hidden" name="IdTransaksi" value="{{ $data->id }}">
                    <input type="hidden" name="IdTransaksiDetail" id="id_transaksi_detail">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100"><i data-feather="check-circle"
                            class="me-2"></i>Bayar Sekarang</button>
                    <button type="button" class="btn btn-secondary w-100 mt-2"
                        data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>

    </div>
</div>
@push('js')
    <script>
        function updateNoRekeningPenerima() {
            var select = document.getElementById('nama_bank_select');
            var input = document.getElementById('no_rekening_penerima');
            var selectedOption = select.options[select.selectedIndex];
            var norek = selectedOption.getAttribute('data-norek') || '';
            input.value = norek;
        }
        // initialize on page load in case select2's default selection
        document.addEventListener('DOMContentLoaded', function() {
            updateNoRekeningPenerima();
            if (window.jQuery) {
                $('#nama_bank_select').on('change', updateNoRekeningPenerima);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-bayar').forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    let cicilanKe = btn.getAttribute('data-cicilanke') || '';
                    let besarCicilan = btn.getAttribute('data-besarcicilan') || '';
                    let elCicilanKe = document.getElementById('input-cicilan-ke');
                    let elBesarCicilan = document.getElementById('input-besar-cicilan');
                    if (elCicilanKe) elCicilanKe.value = cicilanKe;
                    if (elBesarCicilan) elBesarCicilan.value = besarCicilan;
                });
            });
        });
    </script>
@endpush
