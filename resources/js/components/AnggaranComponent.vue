<template>
    <div>
        <table class="table table-borderless table-striped table-hover">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Anggaran Deskripsi</th>
                    <th>Harga Satuan</th>
                    <th>Kuantitas</th>
                    <th>Sumber Dana</th>
                    <th>Sub Total</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(anggaran, index) in anggarans">
                    <td v-if="!anggaran.isDeleted">{{ index + 1 }}</td>
                    <td v-if="!anggaran.isDeleted">
                        {{ anggaran.anggaranDeskripsi }}
                    </td>
                    <td v-if="!anggaran.isDeleted">
                        {{ formatPrice(anggaran.hargaSatuan) }}
                    </td>
                    <td v-if="!anggaran.isDeleted">{{ anggaran.kuantitas }}</td>
                    <td v-if="!anggaran.isDeleted">
                        {{
                            sumberDana.find(
                                (source) =>
                                    source.sumberID === anggaran.sumberID
                            ).sumberDeskripsi
                        }}
                    </td>
                    <td v-if="!anggaran.isDeleted">
                        {{
                            formatPrice(
                                anggaran.hargaSatuan * anggaran.kuantitas
                            )
                        }}
                    </td>
                    <td v-if="!anggaran.isDeleted">
                        <button
                            type="button"
                            @click="editAnggaran(index)"
                            class="btn btn-outline-success btn-sm"
                            data-toggle="modal"
                            data-target="#anggaranModal"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        <button
                            type="button"
                            @click="removeAnggaran(index)"
                            class="btn btn-outline-danger btn-sm"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`anggarans[${index}][anggaranDeskripsi]`"
                        v-model="anggaran.anggaranDeskripsi"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`anggarans[${index}][hargaSatuan]`"
                        v-model="anggaran.hargaSatuan"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`anggarans[${index}][kuantitas]`"
                        v-model="anggaran.kuantitas"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`anggarans[${index}][sumberDeskripsi]`"
                        v-model="
                            sumberDana.find(
                                (source) =>
                                    source.sumberID === anggaran.sumberID
                            ).sumberDeskripsi
                        "
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`anggarans[${index}][sumberID]`"
                        v-model="anggaran.sumberID"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`anggarans[${index}][count_sub_total]`"
                        v-model="anggaran.sub_total"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`anggarans[${index}][id]`"
                        v-model="anggaran.anggaranPropelID"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`anggarans[${index}][isDeleted]`"
                        v-model="anggaran.isDeleted"
                    />
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th colspan="2">Total: {{ formatPrice(total) }}</th>
                </tr>
            </tbody>
        </table>

        <div class="text-center">
            <button
                type="button"
                @click="refreshAnggaran()"
                data-toggle="modal"
                data-target="#anggaranModal"
                class="btn btn-success btn-sm"
            >
                <i class="fas fa-plus"></i> Tambah Data
            </button>
        </div>

        <!-- ANGGARAN MODAL -->
        <div
            class="modal fade"
            id="anggaranModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Form Anggaran
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
                                    for="anggaranDeskripsi"
                                    class="col-form-label"
                                    >Anggaran Deskripsi:</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="anggaranDeskripsi"
                                    name="anggaranDeskripsi"
                                    placeholder="Masukkan Anggaran Deskripsi"
                                    v-model="anggaran.anggaranDeskripsi"
                                />
                            </div>

                            <div class="form-group">
                                <label for="hargaSatuan" class="col-form-label"
                                    >Harga Satuan:</label
                                >
                                <AutoNumericVue
                                    class="form-control"
                                    id="hargaSatuan"
                                    name="hargaSatuan"
                                    v-model="anggaran.hargaSatuan"
                                    :options="{
                                        digitGroupSeparator: '.',
                                        decimalCharacter: ',',
                                        decimalCharacterAlternative: '.',
                                        currencySymbol: '\u00a0Rp',
                                        currencySymbolPlacement: 'p',
                                        roundingMethod: 'U',
                                        minimumValue: '0',
                                        decimalPlaces: '0',
                                    }"
                                >
                                </AutoNumericVue>
                                <!-- <input type="text" class="form-control" id="hargaSatuan" name="hargaSatuan" placeholder="Masukkan Harga Kuantitas" v-model="anggaran.hargaSatuan"> -->
                            </div>

                            <div class="form-group">
                                <label for="kuantitas" class="col-form-label"
                                    >Kuantitas:</label
                                >
                                <input
                                    type="number"
                                    class="form-control"
                                    id="kuantitas"
                                    name="kuantitas"
                                    placeholder="Masukkan Jumlah Barang"
                                    v-model="anggaran.kuantitas"
                                />
                            </div>

                            <div class="form-group">
                                <label for="sumber" class="col-form-label"
                                    >Sumber Dana:</label
                                >
                                <select
                                    name="sumber"
                                    class="form-control"
                                    id="sumber"
                                    v-model="anggaran.sumberID"
                                    placeholder="Pilih Sumber Dana"
                                >
                                    <option value="">Pilih Sumber</option>
                                    <option
                                        v-for="(text, index) in sumberDana"
                                        :value="text.sumberID"
                                    >
                                        {{ text.sumberDeskripsi }}
                                    </option>
                                </select>
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
                            id="submitAnggaran"
                            type="button"
                            class="btn btn-primary"
                            @click="addAnggaran"
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
import AutoNumericVue from "autonumeric-vue/src/components/AutoNumericVue";

export default {
    data: () => ({
        anggarans: [],
        sumberDana: [],
        anggaran: {},
        jenis: [],
        dataKe: "",
    }),
    props: ["sumberDana", "listOfAnggaran", "jenisData"],
    created() {
        this.sumberDana = this.sumberDana;
        this.jenis = this.jenisData;
        this.anggarans = this.listOfAnggaran.map((v) => ({
            ...v,
            isDeleted: false,
        }));
    },
    components: {
        AutoNumericVue,
    },
    methods: {
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(".", ",");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },

        cekform() {},

        addAnggaran() {
            this.anggaran.isDeleted = false;
            let valHargaSatuan = document.getElementById("hargaSatuan").value;
            let valAnggaranDeskripsi =
                document.getElementById("anggaranDeskripsi").value;
            let valKuantitas = document.getElementById("kuantitas").value;
            let valSumberDana = document.getElementById("sumber").value;

            if (this.jenis == "tambah") {
                if (
                    !valHargaSatuan ||
                    !valAnggaranDeskripsi ||
                    !valKuantitas ||
                    !valSumberDana
                ) {
                    alert("Data Tidak Boleh Kosong");
                    document
                        .getElementById("submitAnggaran")
                        .removeAttribute("data-dismiss");
                } else {
                    if (this.dataKe != "") {
                        this.anggarans[this.dataKe - 1] = this.anggaran;
                        let tmpAnggaran = this.anggarans;
                        this.anggarans = [];
                        this.anggarans = tmpAnggaran;
                        this.dataKe = "";
                        document
                            .getElementById("submitAnggaran")
                            .setAttribute("data-dismiss", "modal");
                    } else {
                        // Data Berhasil di Input
                        this.anggarans.push({ ...this.anggaran });
                        document
                            .getElementById("submitAnggaran")
                            .setAttribute("data-dismiss", "modal");
                    }
                }
                this.anggaran = {};
            } else {
                if (
                    !valHargaSatuan ||
                    !valAnggaranDeskripsi ||
                    !valKuantitas ||
                    !valSumberDana
                ) {
                    alert("Data Tidak Boleh Kosong");
                    document
                        .getElementById("submitAnggaran")
                        .removeAttribute("data-dismiss");
                } else {
                    if (this.anggaran && this.anggaran.anggaranPropelID) {
                        let idx = this.anggarans.findIndex(
                            (obj) =>
                                obj.anggaranPropelID ==
                                this.anggaran.anggaranPropelID
                        );
                        this.anggarans[idx] = this.anggaran;
                        document
                            .getElementById("submitAnggaran")
                            .setAttribute("data-dismiss", "modal");
                    } else {
                        // Data Berhasil di Input
                        this.anggarans.push({ ...this.anggaran });
                        document
                            .getElementById("submitAnggaran")
                            .setAttribute("data-dismiss", "modal");
                    }
                }
                this.anggaran = {};
            }
        },
        removeAnggaran(index) {
            this.anggarans.splice(index, 1);
        },
        editAnggaran(index) {
            this.anggaran = { ...this.anggarans[index] };
            this.dataKe = index + 1;
        },
        refreshAnggaran() {
            this.anggaran = {};
        },
    },
    computed: {
        total: function () {
            return this.anggarans.reduce(function (total, anggaran) {
                return total + anggaran.hargaSatuan * anggaran.kuantitas;
            }, 0);
        },
    },
};
</script>
