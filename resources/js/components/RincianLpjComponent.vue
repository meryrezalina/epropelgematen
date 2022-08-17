<template>
    <div>
        <table class="table table-borderless table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kelompok Sasaran</th>
                    <th>Tempat</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(rincian, index) in rincians">
                    <td v-if="!rincian.isDeleted">{{ index + 1 }}</td>
                    <td v-if="!rincian.isDeleted">
                        {{ rincian.rincianDeskripsiLPJ }}
                    </td>
                    <td v-if="!rincian.isDeleted">{{ rincian.tempatLPJ }}</td>
                    <td v-if="!rincian.isDeleted">
                        {{ customDate(rincian.waktuMulaiLPJ) }}
                    </td>
                    <td v-if="!rincian.isDeleted">
                        {{ customDate(rincian.waktuSelesaiLPJ) }}
                    </td>
                    <td v-if="!rincian.isDeleted">
                        <button
                            type="button"
                            @click="editRincian(index)"
                            class="btn btn-outline-success btn-sm"
                            data-toggle="modal"
                            data-target="#rincianModal"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        <button
                            type="button"
                            @click="removeRincian(index)"
                            class="btn btn-outline-danger btn-sm"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][rincianDeskripsiLPJ]`"
                        v-model="rincian.rincianDeskripsiLPJ"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][tempatLPJ]`"
                        v-model="rincian.tempatLPJ"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][waktuMulaiLPJ]`"
                        v-model="rincian.waktuMulaiLPJ"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][waktuSelesaiLPJ]`"
                        v-model="rincian.waktuSelesaiLPJ"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][id]`"
                        v-model="rincian.rincianKeglpjID"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][isDeleted]`"
                        v-model="rincian.isDeleted"
                    />
                </tr>
            </tbody>
        </table>

        <div class="text-center">
            <button
                type="button"
                @click="resfreshIndikator()"
                data-toggle="modal"
                data-target="#rincianModal"
                class="btn btn-success btn-sm"
            >
                <i class="fas fa-plus"></i> Tambah Data
            </button>
        </div>

        <!-- RINCIAN MODAL haha-->
        <div
            class="modal fade"
            id="rincianModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Form Rencana dan Pelaksanaan
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label
                                    for="rincianDeskripsi"
                                    class="col-form-label"
                                    >Kelompok Sasaran:</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="rincianDeskripsiLPJ"
                                    name="rincianDeskripsiLPJ"
                                    placeholder="Masukkan Kelompok Sasaran"
                                    v-model="rincian.rincianDeskripsiLPJ"
                                />
                            </div>

                            <div class="form-group">
                                <label for="tempat" class="col-form-label"
                                    >Tempat Penyelenggaraan:</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="tempatLPJ"
                                    name="tempatLPJ"
                                    placeholder="Masukkan nama tempat"
                                    v-model="rincian.tempatLPJ"
                                />
                            </div>

                            <div class="form-group">
                                <label for="waktuMulai" class="col-form-label"
                                    >Waktu Mulai Penyelenggaraan:</label
                                >
                                <datepicker
                                    format="dd-MMM-yyyy"
                                    id="waktuMulaiLPJ"
                                    placeholder="Pilih tanggal mulai"
                                    name="waktuMulaiLPJ"
                                    v-model="rincian.waktuMulaiLPJ"
                                    input-class="form-control"
                                ></datepicker>
                            </div>

                            <div class="form-group">
                                <label for="waktuSelesai" class="col-form-label"
                                    >Waktu Selesai Penyelenggaraan:</label
                                >
                                <datepicker
                                    format="dd-MMM-yyyy"
                                    id="waktuSelesaiLPJ"
                                    placeholder="Pilih tanggal mulai"
                                    name="waktuSelesaiLPJ"
                                    v-model="rincian.waktuSelesaiLPJ"
                                    input-class="form-control"
                                ></datepicker>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                        <button
                            id="submitRincian"
                            type="button"
                            class="btn btn-primary"
                            @click="addRincian"
                        >
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import datepicker from "vuejs-datepicker";
import moment from "moment";
export default {
    data: () => ({
        rincians: [],
        rincian: {},
    }),
    props: ["listOfRincian"],
    created() {
        this.rincians = this.listOfRincian;
        this.rincians = this.listOfRincian.map((v) => ({
            ...v,
            isDeleted: false,
        }));
    },
    components: {
        datepicker,
    },
    methods: {
        moment(date) {
            return moment(date);
        },
        customDate(date) {
            return moment(date).format("DD-MM-YYYY");
        },
        addRincian() {
            this.rincian.isDeleted = false;
            let valRincianDeskripsiLPJ = document.getElementById(
                "rincianDeskripsiLPJ"
            ).value;
            let valTempatLPJ = document.getElementById("tempatLPJ").value;
            let valWaktuMulaiLPJ =
                document.getElementById("waktuMulaiLPJ").value;
            let valWaktuSelesaiLPJ =
                document.getElementById("waktuSelesaiLPJ").value;
            if (this.rincian && this.rincian.rincianKeglpjID) {
                let idx = this.rincians.findIndex(
                    (obj) => obj.rincianKeglpjID == this.rincian.rincianKeglpjID
                );
                this.rincians[idx] = this.rincian;
            } else {
                //Error Jika Data Kosong
                if (
                    !valRincianDeskripsiLPJ ||
                    !valTempatLPJ ||
                    !valWaktuMulaiLPJ ||
                    !valWaktuSelesaiLPJ
                ) {
                    alert("Data Tidak Boleh Kosong");
                    document
                        .getElementById("submitRincian")
                        .removeAttribute("data-dismiss");
                } else {
                    // Data Berhasil di Input
                    if (
                        moment(this.rincian.waktuSelesaiLPJ).isBefore(
                            this.rincian.waktuMulaiLPJ
                        )
                    ) {
                        alert(
                            "Waktu Mulai tidak boleh lebih dari Waktu Selesai!!!"
                        );
                        document
                            .getElementById("submitRincian")
                            .removeAttribute("data-dismiss");
                    } else {
                        this.rincians.push({ ...this.rincian });
                        document
                            .getElementById("submitRincian")
                            .setAttribute("data-dismiss", "modal");
                    }
                }
            }
            this.rincian = {};
        },
        removeRincian: function (index) {
            this.rincians.splice(index, 1);
        },
        editRincian(index) {
            this.rincian = { ...this.rincians[index] };
        },
        refreshRincian() {
            this.rincian = {};
        },
    },
};
</script>
