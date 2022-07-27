function displayIndikatorCreate(arrayIndikatorJS) {
    $("#dataIndikator").empty();

    let penomoran = 1;
    $.each(arrayIndikatorJS, function (index, data) {
        let tmpPencapaianLPJ = data.hasOwnProperty("pencapaianLPJ")
            ? data.pencapaianLPJ
            : "";
        if (data.isDeleted == "false") {
            $("#dataIndikator").append(
                "<tr>" +
                    "<td>" +
                    penomoran +
                    "</td>" +
                    "<td>" +
                    data.indikatorDeskripsi +
                    "</td>" +
                    "<td>" +
                    data.target +
                    "</td>" +
                    "<td>" +
                    tmpPencapaianLPJ +
                    "</td>" +
                    "<td>" +
                    '<button type="button" class="btn btn-outline-success btn-sm edit-indikator mr-1"  data-id="' +
                    data.indikatorID +
                    "_" +
                    data.indikatorDeskripsi +
                    "_" +
                    data.target +
                    "_" +
                    tmpPencapaianLPJ +
                    '" data-bs-toggle="modal" data-bs-target="#staticBackdrop" value="editIndikator"><i class="fas fa-edit"></i></button>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm hapus-indikator" data-id="' +
                    data.indikatorID +
                    '"><i class="fas fa-times"></i></button>' +
                    "</td>"
            );
            penomoran++;
        }
        $("#dataIndikator").append(
            '<input type="hidden" class="form-control"' +
                'name="indikators[' +
                index +
                '][indikatorDeskripsi]"' +
                'value="' +
                data.indikatorDeskripsi +
                '" >' +
                '<input type = "hidden" class="form-control"' +
                'name="indikators[' +
                index +
                '][target]"' +
                'value="' +
                data.target +
                '" >' +
                '<input type = "hidden" class="form-control"' +
                'name="indikators[' +
                index +
                '][pencapaianLPJ]"' +
                'value="' +
                tmpPencapaianLPJ +
                '" >' +
                "</tr>"
        );
    });
}

function editIndikatorCreate(data, indikatorJS) {
    $("#indikatorDeskripsi").val("");
    $("#target").val("");
    $("#pencapaianLPJ").val("");

    $("#indikatorDeskripsi").val(data[1]);
    $("#target").val(data[2]);
    $("#pencapaianLPJ").val(data[3]);

    let indexArrIndikator = indikatorJS.findIndex(
        (obj) => obj.indikatorID == data[0]
    );

    return [indexArrIndikator, data[0]];
}

function hapusIndikatorCreate(data, indikatorJS) {
    let indexArrIndikator = indikatorJS.findIndex(
        (obj) => obj.indikatorID == data
    );

    return indexArrIndikator;
}
