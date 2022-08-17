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
                        {{ rincian.rincianDeskripsi }}
                    </td>
                    <td v-if="!rincian.isDeleted">{{ rincian.tempat }}</td>
                    <td v-if="!rincian.isDeleted">
                        {{ customDate(rincian.waktuMulai) }}
                    </td>
                    <td v-if="!rincian.isDeleted">
                        {{ customDate(rincian.waktuSelesai) }}
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
                        :name="`rincians[${index}][rincianDeskripsi]`"
                        v-model="rincian.rincianDeskripsi"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][tempat]`"
                        v-model="rincian.tempat"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][waktuMulai]`"
                        v-model="rincian.waktuMulai"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][waktuSelesai]`"
                        v-model="rincian.waktuSelesai"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`rincians[${index}][id]`"
                        v-model="rincian.rincianPropelID"
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
                                    id="rincianDeskripsi"
                                    name="rincianDeskripsi"
                                    placeholder="Masukkan Kelompok Sasaran"
                                    v-model="rincian.rincianDeskripsi"
                                />
                            </div>

                            <div class="form-group">
                                <label for="tempat" class="col-form-label"
                                    >Tempat Penyelenggaraan:</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="tempat"
                                    name="tempat"
                                    placeholder="Masukkan Tempat Penyelenggaraan"
                                    v-model="rincian.tempat"
                                />
                            </div>

                            <div class="form-group">
                                <label for="waktuMulai" class="col-form-label"
                                    >Waktu Mulai Penyelenggaraan:</label
                                >
                                <datepicker
                                    format="dd-MMM-yyyy"
                                    id="waktuMulai"
                                    placeholder="Pilih tanggal mulai"
                                    name="waktuMulai"
                                    v-model="rincian.waktuMulai"
                                    input-class="form-control"
                                ></datepicker>
                            </div>
                            <div class="form-group">
                                <label for="waktuSelesai" class="col-form-label"
                                    >Waktu Selesai Penyelenggaraan:</label
                                >
                                <datepicker
                                    format="dd-MMM-yyyy"
                                    style="background-color: white"
                                    id="waktuSelesai"
                                    placeholder="Pilih tanggal selesai"
                                    name="waktuSelesai"
                                    v-model="rincian.waktuSelesai"
                                    input-class="form-control"
                                    v-bind="style"
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
        jenis: [],
        dataKe: "",
    }),
    props: ["listOfRincian", "jenisData"],
    created() {
        this.rincians = this.listOfRincian;
        this.jenis = this.jenisData;
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
            let valRincianDeskripsi =
                document.getElementById("rincianDeskripsi").value;
            let valTempat = document.getElementById("tempat").value;
            let valWaktuMulai = document.getElementById("waktuMulai").value;
            let valWaktuSelesai = document.getElementById("waktuSelesai").value;

            if (this.jenis == "tambah") {
                //Error Jika Data Kosong
                if (
                    !valRincianDeskripsi ||
                    !valTempat ||
                    !valWaktuMulai ||
                    !valWaktuSelesai
                ) {
                    alert("Data Tidak Boleh Kosong");
                    document
                        .getElementById("submitRincian")
                        .removeAttribute("data-dismiss");
                } else {
                    if (this.dataKe != "") {
                        this.rincians[this.dataKe - 1] = this.rincian;
                        let tmpRincian = this.rincians;
                        this.rincians = [];
                        this.rincians = tmpRincian;
                        this.dataKe = "";
                        document
                            .getElementById("submitRincian")
                            .setAttribute("data-dismiss", "modal");
                    } else {
                        // Data Berhasil di Input
                        if (
                            moment(this.rincian.waktuSelesai).isBefore(
                                this.rincian.waktuMulai
                            )
                        ) {
                            alert(
                                "Waktu Mulai tidak boleh lebih dari Waktu Selesai!!!"
                            );
                            document
                                .getElementById("submitRincian")
                                .removeAttribute("data-dismiss");
                            console.log("moment");
                        } else {
                            this.rincians.push({ ...this.rincian });
                            document
                                .getElementById("submitRincian")
                                .setAttribute("data-dismiss", "modal");
                            console.log("sukses");
                        }
                    }
                }
                this.rincian = {};
            } else {
                //Error Jika Data Kosong
                if (
                    !valRincianDeskripsi ||
                    !valTempat ||
                    !valWaktuMulai ||
                    !valWaktuSelesai
                ) {
                    alert("Data Tidak Boleh Kosong");
                    document
                        .getElementById("submitRincian")
                        .removeAttribute("data-dismiss");
                } else {
                    if (this.rincian && this.rincian.rincianPropelID) {
                        let idx = this.rincians.findIndex(
                            (obj) =>
                                obj.rincianPropelID ==
                                this.rincian.rincianPropelID
                        );
                        this.rincians[idx] = this.rincian;
                        document
                            .getElementById("submitRincian")
                            .setAttribute("data-dismiss", "modal");
                    } else {
                        if (
                            moment(this.rincian.waktuSelesai).isBefore(
                                this.rincian.waktuMulai
                            )
                        ) {
                            alert(
                                "Waktu Mulai tidak boleh lebih dari Waktu Selesai!!!"
                            );
                            document
                                .getElementById("submitRincian")
                                .removeAttribute("data-dismiss");
                            console.log("moment1");
                        } else {
                            // Data Berhasil di Input
                            this.rincians.push({ ...this.rincian });
                            document
                                .getElementById("submitRincian")
                                .setAttribute("data-dismiss", "modal");
                            console.log("sukses1");
                        }
                    }
                }
                this.rincian = {};
            }
        },

        removeRincian: function (index) {
            this.rincians.splice(index, 1);
        },

        editRincian(index) {
            this.rincian = { ...this.rincians[index] };
            this.dataKe = index + 1;
        },
        refreshRincian() {
            this.rincian = {};
        },
    },
};
</script>
