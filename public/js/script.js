// function animasiCoba() {
//     $("#isi").velocity("transition.flipYOut");
// }

function datePick() {
    $('.datepicker').datepicker({
        inline: true
    });
}

function datePickTest() {
    $("#datepicker input").datepicker({ format: "dd-M-yyyy", language: "id" });

}

$(document).ready(function () {
    if ($('.isi').length) {

        // $('.isi').velocity("transition.flipYOut");
        Velocity('.isi', 'transition.slideLeftIn', { duration: 1000 });

    }
    $(".form-control").datepicker({ format: "dd-mm-yyyy" });
    // Data Picker Initialization
    datePick();
    datePickTest();
});