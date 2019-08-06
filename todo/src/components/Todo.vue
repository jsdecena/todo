<template>
  <main class="container">
    <h1>My Simple Todo App</h1>
    <form class="form-horizontal" v-on:submit.prevent="onSubmit()">
      <div class="form-group">
        <label for="todo" class="sr-only">What to do?</label>
        <input v-model="todo" type="text" class="form-control" id="todo" placeholder="What to do today?">
      </div>
      <div id="listing">
        <table class="table">
          <thead>
            <tr>
              <th>Activity</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(list, idx) in lists.data" :key="idx">
              <td>{{list.title}}</td>
              <td>
                <div class="btn-group">
                  <button class="btn btn-success"><i class="fa fa-check"></i> Mark as done</button>
                  <button class="btn btn-danger"><i class="fa fa-trash"></i>Delete todo</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <p v-if="lists.length === 0" class="alert alert-warning">Nothing to do yet, add something!</p>
      </div>
    </form>
  </main>
</template>

<script>
  export default {
    data () {
      return {
        todo: null,
        lists: []
      }
    },
    created () {
      this.listTodo()
    },
    methods: {
      listTodo: function () {
        return this.$http.get('http://127.0.0.1:8000/api/todo').then((response) => {
          this.lists = response.data
      })
      },
      onSubmit: function () {
        this.$http.post('http://127.0.0.1:8000/api/todo', {
          title: this.todo
        }).then((response) => {
          this.listTodo()
          this.todo = null
        })
      }
    }
  }
</script>
