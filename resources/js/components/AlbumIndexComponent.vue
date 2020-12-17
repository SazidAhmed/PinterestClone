<template>
  <div>
    <table class="table table-image">
      <thead>
        <tr>
          <th scope="col">Index</th>
          <th scope="col">Image</th>
          <th scope="col">Name</th>
          <th scope="col">Description</th>
          <th scope="col">Category</th>
          <th scope="col">Edit</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(album, index) in albums" :key="index">
          <td>{{ index + 1 }}</td>
          <td>
            <img :src="'/album/' + album.image" height="60" alt="Sheep" />
          </td>
          <td>{{ album.name }}</td>
          <td>{{ album.description }}</td>
          <td>{{ album.category.name }}</td>
          <td>
            <button
              @click.prevent="edit(album.id)"
              type="button"
              class="btn btn-info btn-sm"
              data-toggle="modal"
              data-target="#editAlbumModal"
            >
              Edit
            </button>
          </td>
          <td>
            <button
              @click.prevent="deleteRecord(album.id)"
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
    <albumedit :editrecord="records" @recordUpdated="recordUpdate"></albumedit>
  </div>
</template>


<script type="text/javascript">
export default {
  data() {
    return {
      albums: [],
      records: [],
    };
  },
  created() {
    this.getalbums();
  },
  methods: {
    getalbums() {
      axios
        .get("/getalbums")
        .then((response) => {
          this.albums = response.data;
        })
        .catch((error) => {
          alert("unable to fetch albums");
        });
    },

    edit(id) {
      axios
        .get("/api/albums/" + id)
        .then((response) => {
          this.records = response.data;
        })
        .catch((error) => {
          alert("unable to to fetch data");
        });
    },

    // emit from editcomponent
    recordUpdate(response) {
      this.albums = response.data;
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
            .delete("/albums/" + id + "/delete")
            .then((response) => {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Data has been deleted",
                showConfirmButton: false,
                timer: 1500,
              });
              this.albums = response.data;
            })
            .catch((error) => {
              console.log(error);
            });
        }
      });
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
            .delete("/api/category/" + id)
            .then((response) => {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Data has been deleted",
                showConfirmButton: false,
                timer: 1500,
              });
              Fire.$emit("recordUpdate");
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