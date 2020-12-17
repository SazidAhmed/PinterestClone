<template>
  <div>
    <div
      class="modal fade"
      id="editCategoryModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="CreateCategoryModal">
              Category Edit Here
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
            <form
              @submit.prevent="updateCategory"
              enctype="multipart/form-data"
            >
              <div class="form-group">
                <label>Name of Category</label>
                <input
                  type="text"
                  name="name"
                  v-model="editrecord.name"
                  class="form-control"
                  maxlength="15"
                  required=""
                />
                <span v-if="allerrors.name" :class="['danger']">
                  {{ allerrors.name[0] }}
                </span>
              </div>
              <div class="form-group">
                <button class="btn btn-success btn-block" type="submit">
                  Submit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script type="text/javascript">
export default {
  props: ["editrecord"],
  data() {
    return {
      allerrors: [],
    };
  },
  created() {},
  methods: {
    updateCategory() {
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };
      let formData = new FormData();
      formData.append("name", this.editrecord.name);
      formData.append("_method", "put");
      axios
        .post("/category/" + this.editrecord.id + "/edit", formData, config)
        .then((response) => {
          $("#editCategoryModal").modal("hide");
          this.$emit("recordUpdated", response);
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Data has been Updated",
            showConfirmButton: false,
            timer: 1500,
          });
        })
        .catch((error) => {
          console.log(error);
          this.allerrors = error.response.data.errors;
        });
    },
  },
};
</script>

<style type="text/css">
.danger {
  color: red;
}
</style>