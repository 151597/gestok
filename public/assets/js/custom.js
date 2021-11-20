$(document).ready(function() {

    // Write your custom Javascript codes here...

});

function salvar(_data, _url, _dataTable) {
    $.ajax({
        data: _data,
        method: 'post',
        url: _url,
        success: function(result) {
            _dataTable.DataTable().ajax.reload();
            //$("#frmData").reset();
        },
        error: function(result) {
            if (result = 'SQLSTATE[23000]') {
                alert('Por Favor Preencha todos os Campos!')
            } else {
                alert("ERRO!");
            }
        }
    })
}

function carregar(_url, _id, _dataTable, _columns) {

}

function clearFields(_form) {
    _form.reset();
}