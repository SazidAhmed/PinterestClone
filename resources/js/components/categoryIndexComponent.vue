<template>
  <div>
    <table class="table table-image">
      <thead>
        <tr>
          <th scope="col">Index</th>
          <th scope="col">Image</th>
          <th scope="col">Edit</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(category, index) in categories" :key="index">
          <td>{{ index + 1 }}</td>
          <td>{{ category.name }}</td>
          <td>
            <button
              @click.prevent="edit(category.id)"
              type="button"
              class="btn btn-info btn-sm"
              data-toggle="modal"
              data-target="#editCategoryModal"
            >
              Edit
            </button>
          </td>
          <td>
            <button
              @click.prevent="deleteRecord(category.id)"
              type="button"
              class="btn btn-danger btn-sm"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <!-- edit component -->
    <catedit :editrecord="records" @recordUpdated="recordUpdate"></catedit>
    <catcreate @recordUpdated="recordUpdate"></catcreate>
  </div>
</template>

<script type="text/javascript">
export default {
  data() {
    return {
      categories: [],
      records: [],
    };
  },
  created() {
    this.getCategories();
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

    edit(id) {
      axios
        .get("/api/category/" + id)
        .then((response) => {
          this.records = response.data;
        })
        .catch((error) => {
          alert("unable to to fetch data");
        });
    },

    recordUpdate(response) {
      this.categories = response.data;
    },

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
  },
};
</script>