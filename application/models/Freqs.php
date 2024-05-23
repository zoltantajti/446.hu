<?php
class Freqs extends CI_Model {
    public function __construct(){ parent::__construct(); }

    public function getList($mit){
        $table = "freq_" . $mit;
        $rows = $this->db->select('id,name,freq,ctcs,dcs,duplex,offset,comment,place,type')->from($table)->get()->result_array();
        $html = '<div class="alert alert-warning">Kérlek, amennyiben van infód a frekvenciáról, írd be!</div>';
        $html .= '<div class="table-responsive"><table class="table table-hover table-sm mb-3"><thead><tr>';
            $html.='<th class="text-center"><input type="text" id="filterPlace" class="form-control-filter" placeholder="Helyszín"/></th>' . 
            '<th><input type="text" id="filterType" class="form-control-filter" placeholder="Típus"/></th>'.
            '<th><input type="text" id="filterName" class="form-control-filter" placeholder="Név"/></th>'.
            '<th><input type="text" id="filterFreq" class="form-control-filter" placeholder="Frekvencia"/></th>' .
            '<th width="10%">CTCSS</th><th width="10%">DCS</th><th width="10%">Eltolás/Felmenő</th><th></th>';
        $html.= '</tr><thead><tbody>';
        foreach($rows as $row){
            if($row['duplex'] == "off"){ $duplex = "-"; }
            elseif($row['duplex'] == "split") { $duplex = $row['offset'] . " Mhz" ;}
            else{$duplex = $row['duplex'] . " " .$row['offset']; };
            $html.='<tr class="' . (($row['id'] % 2) ? "freq-table-even" : "freq-table-odd") . '">';
                $html.='<td>'.$row['place'].'</td>';
                $html.='<td>'.$row['type'].'</td>';
                $html.='<td>'.$row['name'] . (($row['comment'] != null) ? ' <i class="fa-solid fa-comment" class="badge-image" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="badge-tooltip" data-bs-title="'.$row['comment'].'" data-bs-html="true"/></i>' : '' ) . '</td>';
                $html.='<td>'.$row['freq'].' MHz</td>';
                $html.='<td>'.(($row['ctcs'] != null) ? $row['ctcs'] . ' Hz' : '-' ) . '</td>';
                $html.='<td>'.(($row['dcs'] != null) ? $row['dcs'] : '-') .'</td>';
                $html.='<td>'. $duplex .'</td>';
                $html.='<td><a href="#" onClick="fillModal(\''.base64_encode(json_encode($row)).'\',\''.$table.'\');" data-bs-toggle="modal" data-bs-target="#freqModal"><i class="fa fa-fw fa-edit"></i></a></td>';
            $html.= '</tr>';
        };
        $html.= '</tbody></table></div>';
        $html.= 'Exportálás: <a href="export/csv/cottonEar" target="_blank"><i class="fa-solid fa-file-csv"></i></a>';
        $html.= $this->freqModal();
        $html.= '<script>
        function filterTable() {
          let e = $("#filterPlace").val().toLowerCase(),
              t = $("#filterType").val().toLowerCase(),
              i = $("#filterName").val().toLowerCase();
              f = $("#filterFreq").val().toLowerCase();
         
             console.log(t), $("table tbody tr").each((function() {
              let l = $(this).find("td:eq(0)").text().toLowerCase(),
                  o = $(this).find("td:eq(1)").text().toLowerCase(),
                  a = $(this).find("td:eq(2)").text().toLowerCase(),
                  _f = $(this).find("td:eq(3)").text().toLowerCase();
              (l.indexOf(e) > -1 || "" == e) && (o.indexOf(t) > -1 || "" == t) && (a.indexOf(i) > -1 || "" == i) && (_f.indexOf(f) > -1 || "" == f) ? $(this).show(): $(this).hide()
          }))
      }
      $("#filterPlace, #filterType, #filterName, #filterFreq").keyup((function() {
          filterTable()
      }));
        </script>';
        return $html;
    }
    private function freqModal()
    {
        $html = '<div class="modal" id="freqModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="freq-modal-title">Modal title</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="freq-modal-body">
                <input type="hidden" id="tbl" />
                <input type="hidden" id="id" />
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-input-text"></i></span>
                    <input type="text" class="form-control" id="freqName" placeholder="Név" aria-label="Név" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-location-dot"></i></span>
                    <input type="text" class="form-control" id="freqPlace" placeholder="Helyszín" aria-label="Helyszín" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-location-dot"></i></span>
                    <input type="text" list="freqTypes" class="form-control" id="freqType" placeholder="Típus" aria-label="Típus" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-wave-sine"></i></span>
                    <input type="text" class="form-control" id="freq" placeholder="Frekvencia" aria-label="Frekvencia" aria-describedby="basic-addon1">
                    <span class="input-group-text" id="basic-addon1">MHz</span>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">CTCSS</span>
                    <input type="text" class="form-control" id="ctcss" placeholder="CTCSS" aria-label="CTCSS" aria-describedby="basic-addon1">
                    <span class="input-group-text" id="basic-addon1">Hz</span>
                    <span class="input-group-text" id="basic-addon1">DCS</span>
                    <input type="text" class="form-control" id="dcs" placeholder="DCS" aria-label="DCS" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-plus"></i></span>
                    <input type="text" class="form-control" list="duplexTypes" id="duplex" placeholder="Eltolás módja" aria-label="Eltolás módja" aria-describedby="basic-addon1">
                    <input type="text" class="form-control" id="offset" placeholder="Elolás vagy felmenő frek." aria-label="Frekvencia" aria-describedby="basic-addon1">
                    <span class="input-group-text" id="basic-addon1">MHz</span>
                </div>
                <div class="input-group mb-3">
                    <textarea id="comment" class="form-control" placehodler="Megjegyzésed"></textarea>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="freq-modal-cancel" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" id="freq-modal-save" class="btn btn-success">Save changes</button>
            </div>
          </div>
        </div>
      </div>
      <datalist id="freqTypes">
        <option value="Verseny">
        <option value="Taxi">
        <option value="BKK">
        <option value="Légi">
        <option value="Egyéb">
      </datalist>
      <datalist id="duplexTypes">
        <option value="-">
        <option value="+">
        <option value="split">
        <option value="off">
      </datalist>
      <script>
      function fillModal(row, table) {
        let json = JSON.parse(atob(row));
        $("#freq-modal-title").html(json.name + " szerkesztése");
        $("#freqPlace").val(json.place);
        $("#freqType").val(json.type);
        $("#freqName").val(json.name);
        $("#freq").val(json.freq);
        $("#ctcss").val(json.ctcss);
        $("#dcs").val(json.dcs);
        $("#duplex").val(json.duplex);
        $("#offset").val(json.offset);
        $("#comment").val(json.comment);
        $("#tbl").val(table);
        $("#id").val(json.id);
    };
        const saveButton = document.getElementById("freq-modal-save");
        saveButton.addEventListener("click", (event) => {
          let data = {
            id: $("#id").val(),
            tbl: $("#tbl").val(),
            place: $("#freqPlace").val(),
            type: $("#freqType").val(),
            name: $("#freqName").val(),
            freq: $("#freq").val(),
            ctcss: $("#ctcss").val(),
            dcs: $("#dcs").val(),
            duplex: $("#duplex").val(),
            offset: $("#offset").val(),
            comment: $("#comment").val()
          };
          $.post("Rest/updateFreq", data, (_data, status) => {
            $("#freqPlace").val(null);
            $("#freqType").val(null);
            $("#freqName").val(null);
            $("#tbl").val(null);
            $("#id").val(null);
            $("#ctcss").val(null);
            $("#dcs").val(null);
            $("#duplex").val(null);
            $("#offset").val(null);
            $("#comment").val(null);
            window.location.reload();
          });
        });
      </script>';
      return $html;
    }
}