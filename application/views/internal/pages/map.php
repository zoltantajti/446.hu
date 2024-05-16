<div class="container-fluid">
    <div id="windy"></div>
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
                <h5 class="modal-title" id="map-modal-title">Új marker hozzáadása</h5>
                <button type="button" class="btn-close" onClick="$('#addNewMarker').hide();" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="alert alert-warning mb-3">Megkérek, minden Felhasználót, hogy a marker nevéhez a hívójelét adja meg!
                        Ellentkező esetben <b>automatikusan elutasításra kerül!</b>
                    </div>
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
                                <option value="parrot">Papagáj</option>
                                <option value="station">Amatőr átjátszó</option>
                            </select>
                        </div>                        
                    </div>
                    <div class="row mb-3">
                        <label for="title" class="col-sm-4 col-form-label">Elem neve <br/><small>(Hívójeled)</small></label>
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
<div class="modal" id="addNewMarkerTemp" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="map-modal-title">Új marker hozzáadása</h5>
                <button type="button" class="btn-close" onClick="$('#addNewMarkerTemp').hide();" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                        <input type="text" name="lat" class="form-control" id="temp_lat">
                        <input type="text" name="lon" class="form-control" id="temp_lon">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-wave-sine"></i></span>
                        <input type="text" name="freq" class="form-control" id="freq">
                        <span class="input-group-text">MHz</span>
                        <span class="input-group-text">CTCSS</span>
                        <input type="text" name="ctcss" class="form-control" id="ctcss">
                        <span class="input-group-text">Hz</span>
                        <span class="input-group-text">DCS</span>
                        <input type="text" name="dcs" class="form-control" id="dcs">                        
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-play"></i></span>
                        <input type="datetime-local" name="from" class="form-control" id="from" placehodler="Kitelepülés kezdete" title="Kitelepülés kezdete">
                        <span class="input-group-text"><i class="fa-solid fa-flag-checkered"></i></span>
                        <input type="datetime-local" name="to" class="form-control" id="to" placeholder="Kitelepülés vége" title="Kiteleülés vége">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-input-text"></i></span>
                        <input type="text" name="title" class="form-control" id="temp_title" placehodler="Kitelepülés (marker) neve">
                    </div>
                    <div class="row mb-3">
                        <textarea name="content" id="content"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnClearMarkerTemp" data-bs-dismiss="modal">Mégse</button>
                <button type="button" class="btn btn-primary" id="btnSaveMarkerTemp">Mentés</button>
            </div>
        </div>
    </div>
</div>