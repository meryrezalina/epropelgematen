<template>
    <div>
        <table class="table table-borderless table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Indikator Deskripsi</th>
                    <th>Target</th>
                    <th>Pencapaian LPJ</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(indikator, index) in indikators">
                    <td v-if="!indikator.isDeleted">{{ index + 1 }}</td>
                    <td v-if="!indikator.isDeleted">
                        {{ indikator.indikatorDeskripsi }}
                    </td>
                    <td v-if="!indikator.isDeleted">{{ indikator.target }}</td>
                    <td v-if="!indikator.isDeleted">
                        {{ indikator.pencapaianLPJ }}
                    </td>
                    <td v-if="!indikator.isDeleted">
                        <button
                            type="button"
                            @click="editIndikator(index)"
                            class="btn btn-outline-success btn-sm"
                            data-toggle="modal"
                            data-target="#indikatorModal"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        <button
                            type="button"
                            @click="removeIndikator(index)"
                            class="btn btn-outline-danger btn-sm"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`indikators[${index}][indikatorDeskripsi]`"
                        v-model="indikator.indikatorDeskripsi"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`indikators[${index}][target]`"
                        v-model="indikator.target"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`indikators[${index}][pencapaianLPJ]`"
                        v-model="indikator.pencapaianLPJ"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`indikators[${index}][id]`"
                        v-model="indikator.indikatorLpjID"
                    />
                    <input
                        type="hidden"
                        class="form-control"
                        :name="`indikators[${index}][isDeleted]`"
                        v-model="indikator.isDeleted"
                    />
                </tr>
            </tbody>
        </table>

        <div class="text-center">
            <button
                type="button"
                @click="resfreshIndikator()"
                data-toggle="modal"
                data-target="#indikatorModal"
                class="btn btn-success btn-sm"
            >
                <i class="fas fa-plus"></i> Tambah Data
            </button>
        </div>

        <!-- INDIKATOR MODAL haha-->
        <div
            class="modal fade"
            id="indikatorModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Form Indikator Target
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
                                    for="indikatorDeskripsi"
                                    class="col-form-label"
                                    >Indikator Deskripsi:</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="indikatorDeskripsi"
                                    name="indikatorDeskripsi"
                                    placeholder="Masukkan Indikator Deskripsi"
                                    v-model="indikator.indikatorDeskripsi"
                                />
                            </div>

                            <div class="form-group">
                                <label for="target" class="col-form-label"
                                    >Target :</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="target"
                                    name="target"
                                    placeholder="Masukkan Target"
                                    v-model="indikator.target"
                                />
                            </div>

                            <div class="form-group">
                                <label
                                    for="pencapaianLPJ"
                                    class="col-form-label"
                                    >Pencapaian LPJ:</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="pencapaianLPJ"
                                    name="pencapaianLPJ"
                                    placeholder="Masukkan Pencapaian LPJ"
                                    v-model="indikator.pencapaianLPJ"
                                />
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
                            id="submitIndikator"
                            type="button"
                            class="btn btn-primary"
                            @click="addIndikator"
                            data-dismiss="modal"
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
export default {
    data: () => ({
        indikators: [],
        indikator: {},
        jenis: [],
        dataKe: "",
    }),
    props: ["listOfIndikator", "jenisData"],
    created() {
        this.indikators = this.listOfIndikator;
        this.jenis = this.jenisData;
        this.indikators = this.listOfIndikator.map((v) => ({
            ...v,
            isDeleted: false,
        }));
    },
    methods: {
        addIndikator() {
            this.indikator.isDeleted = false;
            let valIndikatorDeskripsi =
                document.getElementById("indikatorDeskripsi").value;
            let valTarget = document.getElementById("target").value;
            let valPencapaianLpj =
                document.getElementById("pencapaianLPJ").value;

            if (this.indikator && this.indikator.indikatorLpjID) {
                let idx = this.indikators.findIndex(
                    (obj) => obj.indikatorLpjID == this.indikator.indikatorLpjID
                );
                this.indikators[idx] = this.indikator;
            } else {
                //Error Jika Data Kosong
                if (!valIndikatorDeskripsi || !valTarget || !valPencapaianLpj) {
                    alert("Data Tidak Boleh Kosong");
                    document
                        .getElementById("submitIndikator")
                        .removeAttribute("data-dismiss");
                } else {
                    // Data Berhasil di Input
                    this.indikators.push({ ...this.indikator });
                    document
                        .getElementById("submitIndikator")
                        .setAttribute("data-dismiss", "modal");
                }
            }
            this.indikator = {};
        },
        removeIndikator: function (index) {
            this.indikators.splice(index, 1);
        },
        editIndikator(index) {
            this.indikator = { ...this.indikators[index] };
            this.dataKe = index + 1;
        },
        refreshIndikator() {
            this.indikator = {};
        },
    },
};
</script>
