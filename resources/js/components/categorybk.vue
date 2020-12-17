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
              @click="createModal"
              >Add New <i class="fa fa-plus"></i
            ></a>
          </div>
          <div class="card-body">
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
                    <a href="" @click.prevent="editModal(category)">
                      <i class="fa fa-edit info"></i>
                    </a>
                    /
                    <a href="">
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
          >
            <div class="modal-body">
              <div class="form-group">
                <label>Name</label>
                <input
                  type="text"
                  name="name"
                  v-model="form.name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                />
                <has-error :form="form" field="name"></has-error>
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
      editmode: false,
      categories: [],
      form: new Form({
        id: "",
        name: "",
      }),
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
        .then(({ data }) => {
          this.categories = data;
        })
        .catch((error) => {
          alert("unable to fetch categories");
        });
    },
    createCategory() {
      this.$Progress.start();
      this.form
        .post("/createCategory")
        .then((data) => {
          Fire.$emit("recordUpdate");
          $("#CreateCategoryModal").modal("hide");
          this.$Progress.finish();
          console.log(data);
        })
        .catch((error) => {
          alert("Validation error");
        });
    },
    updateCategory() {
      this.$Progress.start();
      this.form
        .put("/category/" + this.form.id + "/edit")
        .then(() => {
          Fire.$emit("recordUpdate");
          $("#CreateCategoryModal").modal("hide");
          this.$Progress.finish();
        })
        .then(() => {
          this.$Progress.fail();
        });
    },
    createModal() {
      this.editmode = false;
      this.form.reset();
      $("#CreateCategoryModal").modal("show");
    },
    editModal(category) {
      this.editmode = true;
      this.form.reset();
      $("#CreateCategoryModal").modal("show");
      this.form.fill(category);
    },
  },
};
</script>