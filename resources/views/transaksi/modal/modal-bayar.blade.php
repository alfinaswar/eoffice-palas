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
                    </div>
                    <input type="hidden" name="IdTransaksi" value="{{ $data->id }}">
                    <input type="hidden" name="IdTransaksiDetail" id="id_transaksi_detail">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100"><i data-feather="check-circle"
                            class="me-2"></i>Bayar Sekarang</button>
                    <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
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
    </div>
</div>
