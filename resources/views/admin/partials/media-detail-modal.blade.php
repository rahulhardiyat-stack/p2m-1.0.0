<!-- Media Detail Modal -->
<div class="modal fade" id="mediaDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-search me-2 text-primary"></i>Pratinjau Media</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row no-gutters">
                    <div class="col-md-7 bg-dark d-flex align-items-center justify-content-center" style="min-height: 400px; max-height: 600px;">
                        <div id="media-detail-preview" class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <!-- Image/Video will be injected here -->
                        </div>
                    </div>
                    <div class="col-md-5 p-4 bg-white">
                        <div class="detail-info">
                            <h6 class="text-uppercase small font-weight-bold text-muted mb-3 border-bottom pb-2">Informasi Berkas</h6>
                            <div class="mb-3">
                                <label class="small text-muted mb-0">Nama Berkas</label>
                                <div id="media-detail-name" class="font-weight-bold text-break">-</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="small text-muted mb-0">Ukuran</label>
                                    <div id="media-detail-size" class="font-weight-bold">-</div>
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-0">Dimensi</label>
                                    <div id="media-detail-dimensions" class="font-weight-bold">-</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="small text-muted mb-1">Link URL</label>
                                <div class="input-group">
                                    <input type="text" id="media-detail-url" class="form-control form-control-sm" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm" onclick="copyDetailUrl()">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <form id="media-detail-delete-form" action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus media ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" id="media-detail-delete-btn">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                                <button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showMediaDetails(id) {
    // Show loading or something if needed
    $.get("{{ route('admin.media.json', '') }}/" + id, function(data) {
        populateMediaModal(data);
    });
}

function showMediaDetailsByPath(path) {
    if (!path) return;
    $.get("{{ route('admin.media.json-by-path') }}", { path: path }, function(data) {
        populateMediaModal(data);
    });
}

function populateMediaModal(data) {
    let previewHtml = '';
    console.log("Populating modal with:", data);

    if (data.mime && (data.mime.startsWith('image/') || data.mime === 'image')) {
        previewHtml = `<img src="${data.url}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain; box-shadow: 0 10px 30px rgba(0,0,0,0.5)">`;
    } else if (data.mime && data.mime.startsWith('video/')) {
        previewHtml = `<video controls class="w-100 shadow-lg"><source src="${data.url}" type="${data.mime}"></video>`;
    } else {
        previewHtml = `<div class="text-white text-center"><i class="fas fa-file fa-5x mb-3 opacity-50"></i><br><span>${data.mime || 'Berkas'}</span></div>`;
    }

    $("#media-detail-preview").html(previewHtml);
    $("#media-detail-name").text(data.filename || '-').attr('title', data.filename);
    $("#media-detail-size").text(data.size || '-');
    $("#media-detail-dimensions").text(data.dimensions || '-');
    $("#media-detail-url").val(data.url);
    
    if (data.delete_url) {
        $("#media-detail-delete-form").attr('action', data.delete_url).show();
    } else {
        $("#media-detail-delete-form").hide();
    }

    $("#mediaDetailModal").modal('show');
}

function copyDetailUrl() {
    var copyText = document.getElementById("media-detail-url");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    // Show toast
    if ($('#copyToast').length) {
        $('#copyToast').toast('show');
    } else {
        alert("URL disalin!");
    }
}
</script>
