<!DOCTYPE html>
<html lang="hu" class="h-100" data-bs-theme="light">
<head>
    <base href="<?=base_url()?>" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">    
    <meta name="robots" content="noindex, nofollow" />
    <meta name="author" content="Tajti Zoltán | tajtizoltan.hu">
    <meta name="copyright" content="Minden jog fenntartva - 446.hu" />
    
    <link rel="icon" type="image/png" href="./assets/images/favicon.png" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/fa.all.min.pro.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/446internal.css" />
    
    <title>Regisztráció - 446.HU</title>
</head>
<body class="">
<div class="container">
    <div class="row px-3">
        <div class="col-lg-10 col-xl-9 card flex-row mx-auto px-0">
            <div class="img-left d-none d-md-flex"></div>
            <div class="card-body">
                <h4 class="title text-center mt-4">
                    Regisztráció
                </h4>
                <form method="POST" action="" class="form-box" id="regForm">
                    <?php $this->load->view('internal/register/' . $step); ?>
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                </form>
            </div>
        </div>
    </div>
</div>
<script src="./assets/js/jquery.min.js"></script>
<script src="./assets/js/tinymce/tinymce.min.js"></script>
<script>
    $(document).ready((event) => {
        if($("#country").val() == "Magyarország"){
            $("#county").attr('list', 'countyDataList');
        }
        if(window.location.href.includes("register/4")){
            tinymce.init({
                selector: 'textarea#aboutME',
                plugins: 'lists link',
                toolbar: 'bold italic underline | numlist bullist | link',
                menubar: ''
            })
        }
    });
    $("#nextButton").click((event) => {
        $("#regForm").submit();
    })
    $("#country").keyup((event) => {
        if($("#country").val() == "Magyarország"){
            $("#county").attr('list', 'countyDataList');
        }
    });
    let radios = [];
    let antennas = [];
    let freqs = [];

    $("#radios_input").keyup((event) => {
        event.preventDefault();
        if(event.key == "Enter"){
            const inputElement = document.getElementById("radios_input");
            const inputValue = inputElement.value.trim();
            if (inputValue.length === 0) { inputElement.classList.add("is-invalid"); inputElement.focus(); return; };
            const id = inputValue.replace(/\s+/g, "-");
            const removeLink = document.createElement("a");
            removeLink.href = "javascript:;";
            removeLink.innerHTML = '<i class="fa fa-fw fa-times red"></i>';
            removeLink.addEventListener('click', () => {
                const radioItem = document.getElementById("RI_" + id);
                if (radioItem) {
                    let index = radios.indexOf(inputValue);
                    if(index !== -1){
                        radios.splice(index, 1);
                    }
                    radioItem.remove();
                    updateRadioField();
                    if(radios.length == 0){ $("#form-placeholder-box").show(); };
                }
            });
            const radioItem = document.createElement("span");
            radioItem.classList.add("radioItem");
            radioItem.classList.add("input-group-text");
            radioItem.id = "RI_" + id;
            radioItem.textContent = inputValue;
            radioItem.appendChild(removeLink);       
            if(radios.length == 0){ $("#form-placeholder-box").hide(); };
            $("#radiosFrameBefore").before(radioItem);
            inputElement.value = "";
            inputElement.classList.remove("is-invalid");
            radios.push(inputValue);
            updateRadioField();
        };
        
    });
    $("#antenna_input").keyup((event) => {
        event.preventDefault();
        if(event.key == "Enter"){
            const inputElement = document.getElementById("antenna_input");
            const inputValue = inputElement.value.trim();
            if (inputValue.length === 0) { inputElement.classList.add("is-invalid"); inputElement.focus(); return; };
            const id = inputValue.replace(/\s+/g, "-");
            const removeLink = document.createElement("a");
            removeLink.href = "javascript:;";
            removeLink.innerHTML = '<i class="fa fa-fw fa-times red"></i>';
            removeLink.addEventListener('click', () => {
                const radioItem = document.getElementById("AI_" + id);
                if (radioItem) {
                    let index = antennas.indexOf(inputValue);
                    if(index !== -1){
                        antennas.splice(index, 1);
                    }
                    radioItem.remove();
                    if(antennas.length == 0){ $("#form-placeholder-box-ant").show(); };
                    updateAntennaField();
                }
            });
            const radioItem = document.createElement("span");
            radioItem.classList.add("radioItem");
            radioItem.classList.add("input-group-text");
            radioItem.id = "AI_" + id;
            radioItem.textContent = inputValue;
            radioItem.appendChild(removeLink);       
            if(antennas.length == 0){ $("#form-placeholder-box-ant").hide(); };
            $("#antennaFrameBefore").before(radioItem);
            inputElement.value = "";
            inputElement.classList.remove("is-invalid");
            antennas.push(inputValue);
            updateAntennaField();
        };
    });
    $("#freq_input").keyup((event) => {
        event.preventDefault();
        if(event.key == "Enter"){
            if(freqs.length < 3){
                const inputElement = document.getElementById("freq_input");
                const inputValue = inputElement.value.trim();
                if (inputValue.length === 0) { inputElement.classList.add("is-invalid"); inputElement.focus(); return; };
                const id = inputValue.replace(/\s+/g, "-");
                const removeLink = document.createElement("a");
                removeLink.href = "javascript:;";
                removeLink.innerHTML = '<i class="fa fa-fw fa-times red"></i>';
                removeLink.addEventListener('click', () => {
                    const radioItem = document.getElementById("FI_" + id);
                    if (radioItem) {
                        let index = freqs.indexOf(inputValue);
                        if(index !== -1){
                            freqs.splice(index, 1);
                        }
                        radioItem.remove();
                        if(freqs.length == 0){ $("#form-placeholder-box-freq").show(); };
                        updateFreqField();
                        if(freqs.length < 3){
                            inputElement.disabled = false;
                        }
                    }
                });
                const radioItem = document.createElement("span");
                radioItem.classList.add("radioItem");
                radioItem.classList.add("input-group-text");
                radioItem.id = "FI_" + id;
                radioItem.textContent = inputValue;
                radioItem.appendChild(removeLink);       
                if(freqs.length == 0){ $("#form-placeholder-box-freq").hide(); };
                $("#freqFrameBefore").before(radioItem);
                inputElement.value = "";
                inputElement.classList.remove("is-invalid");
                freqs.push(inputValue);
                updateFreqField();
                if(freqs.length === 3){
                    inputElement.disabled = true;
                }
            }else if(freqs.length == 3){ 
                const inputElement = document.getElementById("freq_input");
                inputElement.classList.add("is-invalid");
                inputElement.value = "";
            }
        };
    });
    function updateRadioField(){
        $("#radios").val(JSON.stringify(radios));
    }
    function updateAntennaField(){
        $("#antennas").val(JSON.stringify(antennas));
    }
    function updateFreqField(){
        $("#freqs").val(JSON.stringify(freqs));
    }
</script>
<?php $this->load->view('internal/register/datalist'); ?>
</body>
</html>