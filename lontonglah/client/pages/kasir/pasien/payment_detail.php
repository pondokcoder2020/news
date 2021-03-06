<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white align-items-center">
                <div class="row info-kwitansi">
                    <div class="col-6">
                        <span class="card-header__title" id="nama-pasien-faktur"></span>
                    </div>
                    <div class="col-6">
                        <span class="card-header__title" id="pegawai-faktur"></span>
                    </div>
                </div>
                <br />
                <div class="row info-kwitansi">
                    <div class="col-6">
                        <span class="card-header__title" id="tanggal-faktur"></span>
                    </div>
                    <div class="col-6">
                        <span class="card-header__title" id="poli"></span>
                    </div>
                </div>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				
			</div>
			<div class="card-body tab-content">
				<div class="tab-pane active show fade" id="biaya-terkini">
					<table class="table table-bordered table-striped largeDataType" id="invoice_detail_history">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content"></th>
								<th class="wrap_content">No</th>
								<th>Item</th>
								<th class="wrap_content">Jlh</th>
								<th class="number_style" style="max-width: 200px; width: 200px">Harga</th>
								<th class="number_style" style="max-width: 200px; width: 200px">Subtotal</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr>
								<td colspan="4" rowspan="3" id="keterangan-faktur">
								</td>
								<td class="text-right">
									Total
								</td>
								<td id="total-faktur" class="text-right">0.00</td>
							</tr>
							<tr>
								<td class="text-right">Diskon</td>
								<td id="diskon-faktur" class="text-right">
									
								</td>
							</tr>
							<tr>
								<td class="text-right">
									Grand Total
								</td>
								<td id="grand-total-faktur" class="text-right">0.00</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>