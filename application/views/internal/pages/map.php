<div class="container-fluid">
    <div id="map"></div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toastTitle">Köszi :) </strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastBody">
            Köszönjük a visszajelzésed
        </div>
    </div>
</div>
<div class="modal" id="addNewMarker" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Új marker hozzáadása</h5>
                <button type="button" class="btn-close" onClick="$('#addNewMarker').hide();" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <label for="place" class="col-sm-4 col-form-label">Hol található?</label>
                        <div class="col-sm-4">
                            <input type="text" name="lat" class="form-control" id="lat">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="lon" class="form-control" id="lon">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="type" class="col-sm-4 col-form-label">Típus</label>
                        <div class="col-sm-8">
                            <select name="type" class="form-control" id="type">
                                <option value="mobile_radio">Kézi rádió</option>
                                <option value="desktop_radio">Asztali rádió</option>
                                <option value="parrot">Papagáj</option>
                                <option value="station">Amatőr átjátszó</option>
                            </select>
                        </div>                        
                    </div>
                    <div class="row mb-3">
                        <label for="title" class="col-sm-4 col-form-label">Elem neve</label>
                        <div class="col-sm-8">
                            <input type="text" name="title" class="form-control" id="title">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="description" class="col-sm-4 col-form-label">
                            Leírás<br/>
                            <span class="small">HTML kódok engedélyezve</span>
                        </label>
                        <div class="col-sm-8">
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnClearMarker" data-bs-dismiss="modal">Mégse</button>
                <button type="button" class="btn btn-primary" id="btnSaveMarker">Mentés</button>
            </div>
        </div>
    </div>
</div>