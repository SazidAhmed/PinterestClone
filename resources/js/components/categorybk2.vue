<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <a
              href=""
              class="btn btn-success float-right"
              data-toggle="modal"
              @click.prevent="createModal"
              >Add New <i class="fa fa-plus"></i
            ></a>
          </div>
          <div class="card-body">
            {{ records }}
            <table class="table table-image">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Name</th>
                  <th scope="col">created At</th>
                  <th scope="col">Modify</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="category in categories" :key="category.id">
                  <td>{{ category.id }}</td>
                  <td>{{ category.name | upText }}</td>
                  <td>{{ category.created_at | myDate }}</td>
                  <td>
                    <a href="" @click.prevent="editModal(category.id)">
                      <i class="fa fa-edit info"></i>
                    </a>
                    /
                    <a href="" @click.prevent="deleteRecord(category.id)">
                      <i class="fa fa-trash danger"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div
      class="modal fade"
      id="CreateCategoryModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">
              Add New Category
            </h5>
            <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">
              Update Category
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
          <form
            @submit.prevent="editmode ? updateCategory() : createCategory()"
            enctype="multipart/form-data"
          >
            <div class="modal-body">
              <div v-if="!editmode" class="form-group">
                <label>Name</label>
                <input
                  type="text"
                  name="name"
                  v-model="name"
                  class="form-control"
                  maxlength="15"
                  required=""
                />
                <span v-if="allerrors.name" :class="['danger']">
                  {{ allerrors.name[0] }}
                </span>
              </div>
              <div v-if="editmode" class="form-group">
                <label>Name</label>
                <input
                  type="text"
                  name="name"
                  v-model="records.name"
                  class="form-control"
                  maxlength="15"
                  required=""
                />
                <span v-if="allerrors.name" :class="['danger']">
                  {{ allerrors.name[0] }}
                </span>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button v-show="editmode" type="submit" class="btn btn-success">
                Update
              </button>
              <button v-show="!editmode" type="submit" class="btn btn-success">
                Create
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      id: "",
      name: "",
      editmode: false,
      categories: [],
      allerrors: [],
      records: [],
    };
  },
  created() {
    this.getCategories();
    Fire.$on("recordUpdate", () => {
      this.getCategories();
    });
    // setInterval(() => this.getCategories(), 3000);
  },
  methods: {
    getCategories() {
      axios
        .get("/api/categories")
        .then((response) => {
          this.categories = response.data;
        })
        .catch((error) => {
          alert("unable to fetch categories");
        });
    },
    createCategory() {
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };
      this.$Progress.start();
      let formData = new FormData();
      formData.append("name", this.name);
      axios
        .post("/createCategory", formData, config)
        .then((response) => {
          (this.name = ""), Fire.$emit("recordUpdate");
          $("#CreateCategoryModal").modal("hide");
          this.$Progress.finish();
          Swal.fire({
            position: "center",
            icon: "success",
            title: "Data has been Created",
            showConfirmButton: false,
            timer: 1500,
          });
        })
        .catch((error) => {
          console.log(error);
          this.allerrors = error.response.data.errors;
        });
    },
    updateCategory() {
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };
      this.$Progress.start();
      let formData = new FormData();
      formData.append("name", this.records.name);
      formData.append("_method", "put");
      axios
        .post("/category/" + this.records.id + "/edit", formData, config)
        .then((response) => {
          (this.name = ""), Fire.$emit("recordUpdate");
          $("#CreateCategoryModal").modal("hide");
          this.$Progress.finish();
          Swal.fire({
            position: "center",
            icon: "success",
            title: "Data has been Created",
            showConfirmButton: false,
            timer: 1500,
          });
        })
        .catch((error) => {
          console.log(error);
          this.allerrors = error.response.data.errors;
        });
    },
    createModal() {
      this.editmode = false;
      $("#CreateCategoryModal").modal("show");
    },
    editModal(id) {
      axios
        .get("/api/category/" + id)
        .then((response) => {
          this.records = response.data;
          this.editmode = true;
          $("#CreateCategoryModal").modal("show");
        })
        .catch((error) => {
          alert("unable to to fetch data");
        });
    },

    //  editModal(category) {
    //   this.records = category;
    //   this.editmode = true;
    //   $("#CreateCategoryModal").modal("show");
    // },

    deleteRecord(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.value) {
          axios
            .delete("/category/" + id + "/delete")
            .then((response) => {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Data has been deleted",
                showConfirmButton: false,
                timer: 1500,
              });
              this.categories = response.data;
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
    },

    deleteRecords(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success ",
          cancelButton: "btn btn-danger float-right",
        },
      });
      swalWithBootstrapButtons
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No, cancel!",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            axios.delete("/category/" + id + "/delete").then((response) => {
              swalWithBootstrapButtons.fire(
                "Deleted!",
                "Your file has been deleted.",
                "success"
              );
              Fire.$emit("recordUpdate");
            });
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              "Cancelled",
              "Your imaginary file is safe :)",
              "error"
            );
          }
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